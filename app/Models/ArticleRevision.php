<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ArticleRevision extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'description', 'body', "article_id"];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
