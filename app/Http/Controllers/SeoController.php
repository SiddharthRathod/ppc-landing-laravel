<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * /sitemap.xml: every public URL, so Google finds new posts quickly.
     * Leaves out unpublished pages, drafts, scheduled posts and anything set to "noindex".
     */
    public function sitemap(): Response
    {
        $pages = Page::published()
            ->where('robots', 'not like', 'noindex%')
            ->get();

        $posts = Post::published()->latest('published_at')->get();

        return response()
            ->view('seo.sitemap', ['pages' => $pages, 'posts' => $posts])
            ->header('Content-Type', 'application/xml');
    }

    /**
     * /robots.txt: generated (not a static file) so it can contain the full sitemap URL.
     * Outside production the whole site is blocked, so a staging copy never ends up in Google.
     */
    public function robots(): Response
    {
        $lines = app()->isProduction()
            ? ['User-agent: *', 'Disallow: /admin', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n")
            ->header('Content-Type', 'text/plain');
    }
}
