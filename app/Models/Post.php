<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'image',
        'excerpt',
        'body',
        'meta_title',
        'meta_description',
        'canonical_url',
        'og_image',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    /**
     * Only posts with a publish date that has arrived (no drafts, no scheduled posts).
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /**
     * Posts with the given status (used by the admin filter).
     */
    #[Scope]
    protected function withStatus(Builder $query, PostStatus $status): void
    {
        match ($status) {
            PostStatus::Draft => $query->whereNull('published_at'),
            PostStatus::Scheduled => $query->where('published_at', '>', now()),
            PostStatus::Published => $query->published(),
        };
    }

    public function imageUrl(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    /**
     * Values for the layout's <head> tags; admin SEO fields win, then sensible fallbacks.
     *
     * @return array<string, string|null>
     */
    public function seo(): array
    {
        return [
            'title' => $this->meta_title ?: $this->title.' | Professional Photography Company',
            'description' => $this->meta_description ?: $this->excerpt,
            'canonical' => $this->canonical_url ?: route('blog.show', $this),
            'og_image' => $this->og_image ? Storage::disk('public')->url($this->og_image) : $this->imageUrl(),
        ];
    }

    public function status(): PostStatus
    {
        return match (true) {
            $this->published_at === null => PostStatus::Draft,
            $this->published_at->isFuture() => PostStatus::Scheduled,
            default => PostStatus::Published,
        };
    }
}
