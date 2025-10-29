<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\ArticleRevision;

class ArticleObserver
{
    /**
     * Handle the Article "updating" event.
     *
     * @param  \App\Models\Article  $article
     * @return void
     */
    public function updating(Article $article)
    {
        // Only create a revision if the article already exists
        if ($article->exists) {
            // Get the original (current) values before update
            $original = $article->getOriginal();
            
            // Only create a revision if relevant fields have changed
            $trackedFields = ['title', 'slug', 'description', 'body'];
            $hasChanges = false;
            
            foreach ($trackedFields as $field) {
                if ($article->isDirty($field)) {
                    $hasChanges = true;
                    break;
                }
            }
            
            if ($hasChanges) {
                ArticleRevision::create([
                    'article_id' => $article->id,
                    'title' => $original['title'],
                    'slug' => $original['slug'],
                    'description' => $original['description'],
                    'body' => $original['body'],
                ]);
            }
        }
    }
}
