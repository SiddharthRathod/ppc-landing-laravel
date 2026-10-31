<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    /** @use HasFactory<\Database\Factories\PageFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'canonical_url',
        'og_image',
        'robots',
        'is_published',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Only pages visible on the site.
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * The page's public URL: "home" is the site root, others are /{slug}.
     */
    public function url(): string
    {
        return $this->slug === 'home' ? route('home') : url($this->slug);
    }

    /**
     * The data of the first section of this type, e.g. block('contact_cta'), or null.
     *
     * @return array<string, mixed>|null
     */
    public function block(string $type): ?array
    {
        return collect($this->content ?? [])->firstWhere('type', $type)['data'] ?? null;
    }

    /**
     * Values for the layout's <head> tags; admin SEO fields first, then fallbacks.
     *
     * @return array<string, string|null>
     */
    public function seo(): array
    {
        return [
            'title' => $this->meta_title ?: $this->title,
            'description' => $this->meta_description,
            'canonical' => $this->canonical_url ?: $this->url(),
            // No sharing image set? Use the hero photo, so shared links still show a picture.
            'og_image' => media_url($this->og_image ?: ($this->block('hero')['image'] ?? null)),
            'robots' => $this->robots,
        ];
    }
}
