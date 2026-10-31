<?php

namespace App\Providers;

use App\Contracts\CrmClient;
use App\Models\Post;
use App\Models\SiteSetting;
use App\Services\BreadcrumbService;
use App\Services\Crm\LogCrmClient;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Pick the CRM class from config/crm.php. Adding a real CRM = one new class + one line here.
        $this->app->bind(CrmClient::class, fn () => match (config('crm.driver')) {
            'log' => new LogCrmClient,
            default => throw new InvalidArgumentException('Unknown CRM_DRIVER: '.config('crm.driver')),
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $settings in every site view (header, footer, WhatsApp button, contact blocks).
        // once(): one database query per request, however many views ask for it.
        View::composer(['layouts.site', 'pages.*', 'partials.*', 'sections.*'], function ($view) {
            $view->with('settings', once(fn () => SiteSetting::current()));
        });

        // "Latest blog posts" section: the 3 latest published posts.
        View::composer('sections.blog', function ($view) {
            $view->with('latestPosts', Post::published()->latest('published_at')->take(3)->get());
        });

        // Breadcrumbs on every page that uses the site layout, built from the current route.
        // Only one trail item (or none) means home or an unknown page: no breadcrumbs, no JSON-LD.
        View::composer('layouts.site', function ($view) {
            $breadcrumbs = app(BreadcrumbService::class);
            $items = $breadcrumbs->forRequest(request());

            $view->with([
                'breadcrumbs' => $items,
                'breadcrumbSchema' => count($items) > 1 ? $breadcrumbs->schema($items) : null,
            ]);
        });
    }
}
