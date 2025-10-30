<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\ArticleRevision;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticleRevisionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Article $article): bool
    {
        
        return $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ArticleRevision  $revision
     * @return bool
     */
    public function view(User $user, $revision): bool
    {
        // If we get an ArticleRevision instance
        if ($revision instanceof ArticleRevision) {
            return $user->id === $revision->article->user_id;
        }
        
        // If we get an array with [ArticleRevision, Article]
        if (is_array($revision) && isset($revision[0]) && $revision[0] instanceof ArticleRevision) {
            return $user->id === $revision[0]->article->user_id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can revert to a revision.
     */
    public function revert(User $user, Article $article): bool
    {
        // Only the article author can revert to a previous revision
        return $user->id === $article->user_id;
    }
}
