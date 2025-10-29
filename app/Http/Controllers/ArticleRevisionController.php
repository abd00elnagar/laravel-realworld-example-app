<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleRevision;
use Illuminate\Http\Request;

class ArticleRevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Article $article)
    {
        $revisions = $article->revisions()->latest('created_at')->get();
        return response()->json([
            'revisions' => $revisions,
            'count' => $revisions->count(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article, $revisionId)
    {
        $revision = ArticleRevision::where('id', $revisionId)
            ->where('article_id', $article->id)
            ->firstOrFail();
            
        return response()->json([
            'revision' => $revision,
        ]);
    }



    public function revert(Article $article, $revisionId)
    {
        try {
            $revision = ArticleRevision::where('id', $revisionId)
                ->where('article_id', $article->id)
                ->first();

            if (!$revision) {
                return response()->json([
                    'error' => 'Revision not found',
                ], 404);
            }
            // // For Debugging
            // return response()->json([
            //     'debug' => true,
            //     'revision' => $revision,
            //     'current_article' => $article->only(['title', 'slug', 'description', 'body'])
            // ]);

            ArticleRevision::create([
                'article_id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'description' => $article->description,
                'body' => $article->body,
            ]);

            $article->update([
                'title' => $revision->title,
                'slug' => $revision->slug,
                'description' => $revision->description,
                'body' => $revision->body,
            ]);

            return response()->json([
                'message' => 'Article reverted to revision',
                'article' => $article,
            ]);


        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ],
        500
        );
        }
    }
}
