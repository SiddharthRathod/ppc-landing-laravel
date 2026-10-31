<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\Request;

/**
 * Builds the breadcrumb trail automatically from the current route and
 * its bound model, so no page has to set breadcrumbs by hand.
 *
 *   contact     → Home › Contact Us
 *   blog.index  → Home › Blog
 *   blog.show   → Home › Blog › {post title}
 *   home, 404s, unknown routes → no breadcrumbs
 *
 * URLs come from route(), the same helper as the canonical tag, so they
 * are absolute and always match the page's canonical URL.
 */
class BreadcrumbService
{
    /**
     * Inner pages without a model: route name => label.
     */
    private const PAGES = [
        'contact' => 'Contact Us',
        'blog.index' => 'Blog',
    ];

    /**
     * @return list<array{label: string, url: string}>
     */
    public function forRequest(Request $request): array
    {
        $route = $request->route();

        $trail = match ($route?->getName()) {
            'contact', 'blog.index' => [$this->item($route->getName())],
            'blog.show' => [$this->item('blog.index'), $this->post($route->parameter('post'))],
            default => [],
        };

        if ($trail === []) {
            return [];
        }

        // Home first; unique() by URL guarantees one path, without repeated steps.
        return collect([['label' => 'Home', 'url' => route('home')], ...$trail])
            ->unique('url')
            ->values()
            ->all();
    }

    /**
     * schema.org BreadcrumbList (JSON-LD) built from the same trail as the visible breadcrumbs.
     *
     * @param  list<array{label: string, url: string}>  $items
     * @return array<string, mixed>
     */
    public function schema(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn (array $item, int $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1, // positions start at 1
                'name' => $item['label'],
                'item' => $item['url'],
            ])->all(),
        ];
    }

    /**
     * @return array{label: string, url: string}
     */
    private function item(string $routeName): array
    {
        return ['label' => self::PAGES[$routeName], 'url' => route($routeName)];
    }

    /**
     * @return array{label: string, url: string}
     */
    private function post(Post $post): array
    {
        return ['label' => $post->title, 'url' => route('blog.show', $post)];
    }
}
