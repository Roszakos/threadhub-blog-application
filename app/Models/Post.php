<?php

namespace App\Models;

use App\Models\User;
use App\Models\Vote;
use App\Models\Comment;
use App\Models\PostSection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'user_id',
        'title',
        'image',
        'slug'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(100);
    }

    public function incrementViewCount()
    {
        $this->views++;
        return $this->save();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PostSection::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
