<?php

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use App\Models\Page;
use App\Models\Post;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('pages.blog.index', [
            'posts' => Post::published()->latest('published_at')->paginate(9),
            'seo' => [
                'title' => 'Guides | Professional Photography Company',
                'description' => 'Guides to corporate video, safety films, timelapse and photography for companies in Saudi Arabia.',
            ],
        ]);
    }

    public function show(Post $post): View
    {
        // Drafts and scheduled posts are not public yet. (Deleted posts are
        // already excluded by SoftDeletes, so they 404 in route binding.)
        abort_unless($post->status() === PostStatus::Published, 404);

        return view('pages.blog.show', [
            'post' => $post,
            'seo' => $post->seo(),
            // Reuse the home page's "Get a quote" section under every article.
            'quoteCta' => Page::where('slug', 'home')->first()?->block('contact_cta'),
        ]);
    }
}
