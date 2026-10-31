<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return $this->show('home');
    }

    public function contact(): View
    {
        return $this->show('contact');
    }

    /**
     * Render a page's sections (Builder blocks) in the order set in the admin.
     */
    private function show(string $slug): View
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        return view('pages.page', [
            'page' => $page,
            'seo' => $page->seo(),
        ]);
    }
}
