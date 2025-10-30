<?php

namespace Database\Factories;

use App\Models\ArticleRevision;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleRevisionFactory extends Factory
{
    protected $model = ArticleRevision::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'description' => $this->faker->paragraph,
            'body' => $this->faker->paragraphs(3, true),
            'article_id' => \App\Models\Article::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
