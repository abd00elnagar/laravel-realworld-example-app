<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleRevision;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleRevisionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Article $article;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->article = Article::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Test Article',
            'slug' => 'test-article',
            'description' => 'Test Description',
            'body' => 'Test Body'
        ]);
        
        // Create some revisions for testing
        ArticleRevision::factory()->count(3)->create([
            'article_id' => $this->article->id,
            'title' => 'Test Article',
            'slug' => 'test-article',
            'description' => 'Test Description',
            'body' => 'Test Body'
        ]);
    }

    /** @test */
    public function it_can_list_revisions_for_an_article()
    {
        $response = $this->actingAs($this->user)
            ->getJson("/api/articles/{$this->article->slug}/revisions");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'revisions' => [
                    '*' => ['id', 'title', 'slug', 'description', 'body', 'created_at', 'updated_at']
                ],
                'revisionsCount'
            ])
            ->assertJsonCount(3, 'revisions')
            ->assertJson(['revisionsCount' => 3]);
    }

    /** @test */
    public function it_can_retrieve_a_specific_revision()
    {
        $revision = ArticleRevision::first();
        
        $response = $this->actingAs($this->user)
            ->getJson("/api/articles/{$this->article->slug}/revisions/{$revision->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'revision' => [
                    'id', 'title', 'slug', 'description', 'body', 'created_at', 'updated_at',
                    'article' => ['id', 'title', 'slug']
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_revision()
    {
        $response = $this->actingAs($this->user)
            ->getJson("/api/articles/{$this->article->slug}/revisions/9999");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_creates_revision_when_article_is_updated()
    {
        // We start with 3 revisions from setUp
        $initialRevisionCount = 3;
        
        // Get the current article data before update
        $originalArticle = $this->article->fresh();
        
        $updateData = [
            'article' => [
                'title' => 'Updated Title ' . time(),
                'description' => 'Updated Description',
                'body' => 'Updated Body'
            ]
        ];

        $response = $this->actingAs($this->user)
            ->putJson("/api/articles/{$this->article->slug}", $updateData);

        $response->assertStatus(200);
        
        // Verify a new revision was created with the old article data
        $this->assertDatabaseHas('article_revisions', [
            'article_id' => $this->article->id,
            'title' => $originalArticle->title,
            'description' => $originalArticle->description,
            'body' => $originalArticle->body
        ]);
        
        // Verify the article was updated with new data
        $this->assertDatabaseHas('articles', [
            'id' => $this->article->id,
            'title' => $updateData['article']['title'],
            'description' => $updateData['article']['description'],
            'body' => $updateData['article']['body']
        ]);
    }

    /** @test */
    public function it_prevents_unauthorized_access_to_revisions()
    {
        $revision = ArticleRevision::first();
        
        // Try to access revisions without authentication - should be 401 (Unauthenticated)
        $response = $this->getJson("/api/articles/{$this->article->slug}/revisions");
        $response->assertStatus(401);
        
        // Try to access a specific revision without authentication - should be 401
        $response = $this->getJson("/api/articles/{$this->article->slug}/revisions/{$revision->id}");
        $response->assertStatus(401);
        
        // Try to revert a revision without authentication - should be 401
        $response = $this->postJson("/api/articles/{$this->article->slug}/revisions/{$revision->id}/revert");
        $response->assertStatus(401);
        
        // Test with an authenticated non-author user
        $otherUser = User::factory()->create();
        
        // Try to access revisions as non-author - should be 403 (Forbidden)
        $response = $this->actingAs($otherUser)
            ->getJson("/api/articles/{$this->article->slug}/revisions");
        $response->assertStatus(403);
        
        // Try to access a specific revision as non-author - should be 403
        $response = $this->actingAs($otherUser)
            ->getJson("/api/articles/{$this->article->slug}/revisions/{$revision->id}");
        $response->assertStatus(403);
        
        // Try to revert a revision as non-author - should be 403
        $response = $this->actingAs($otherUser)
            ->postJson("/api/articles/{$this->article->slug}/revisions/{$revision->id}/revert");
        $response->assertStatus(403);
    }
}
