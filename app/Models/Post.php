<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'featured_image',
        'video_url',
        'status',
        'view_count',
        'comments_count',
        'author_id',
        'category_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'publish')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function getTitleAttribute($value)
    {
        return html_entity_decode(html_entity_decode($value ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function getExcerptAttribute($value)
    {
        return html_entity_decode(html_entity_decode($value ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function getYoutubeIdAttribute(): ?string
    {
        if (empty($this->video_url)) {
            return null;
        }
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts|live)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->video_url, $match);
        return $match[1] ?? null;
    }

    public function getYoutubeThumbnailUrlAttribute(): ?string
    {
        $id = $this->youtube_id;
        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
    }

    public function getFeaturedImageUrlAttribute()
    {
        $ytThumb = $this->youtube_thumbnail_url;

        // 1. Explicit full HTTP URL
        if ($this->featured_image && str_starts_with($this->featured_image, 'http')) {
            return $this->featured_image;
        }

        // 2. Local uploaded file (storage or public)
        if ($this->featured_image) {
            $storagePath = storage_path('app/public/' . $this->featured_image);
            if (file_exists($storagePath)) {
                return asset('storage/' . $this->featured_image);
            }
            $publicPath = public_path($this->featured_image);
            if (file_exists($publicPath)) {
                return asset($this->featured_image);
            }
        }

        // 3. YouTube Thumbnail from Video URL
        if ($ytThumb) {
            return $ytThumb;
        }

        // 4. Default fallback
        return 'https://picsum.photos/seed/post' . $this->id . '/800/450';
    }
}
