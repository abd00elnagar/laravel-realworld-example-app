<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleRevision;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleRevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Get all revisions for an article
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function index(Article $article): JsonResponse
    {
        try {
            $this->authorize('viewAny', [ArticleRevision::class, $article]);
            
            $revisions = $article->revisions()
                ->latest('created_at')
                ->get();
                
            return response()->json([
                'revisions' => $revisions,
                'revisionsCount' => $revisions->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch revisions',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * Get a specific revision of an article
     *
     * @param Article $article
     * @param int $revisionId
     * @return JsonResponse
     */
    public function show(Article $article, $revisionId): JsonResponse
    {
        try {
            $revision = ArticleRevision::where('id', $revisionId)
                ->where('article_id', $article->id)
                ->firstOrFail();
                
            $this->authorize('view', [$revision, $article]);
                
            return response()->json([
                'revision' => $revision,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Revision not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch revision',
                'message' => $e->getMessage()
            ]);
        }
    }



    /**
     * Revert an article to a specific revision
     *
     * @param Article $article
     * @param int $revisionId
     * @return JsonResponse
     */
    public function revert(Article $article, $revisionId): JsonResponse
    {
        try {
            // Find the revision
            $revision = ArticleRevision::where('id', $revisionId)
                ->where('article_id', $article->id)
                ->firstOrFail();

                $article->update([
                    'title' => $revision->title,
                    'slug' => $revision->slug,
                    'description' => $revision->description,
                    'body' => $revision->body,
                ]);
                return response()->json([
                    'article' => $article,
                    'message' => 'Article successfully reverted to the selected revision',
                ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Revision not found'
            ], 404);
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'error' => 'You are not authorized to perform this action'
            ], 403);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to revert article',
                'message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
