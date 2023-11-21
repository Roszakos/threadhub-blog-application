<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'post_id'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
