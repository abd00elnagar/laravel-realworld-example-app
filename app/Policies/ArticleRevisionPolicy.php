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
        // Only the article author can view revisions
        return $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ArticleRevision  $revision
     * @return bool
     */
    public function view(User $user, ArticleRevision $revision): bool
    {
        // Only the article author can view a specific revision
        return $user->id === $revision->article->user_id;
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
