<?php

namespace App\Providers;

use App\Contracts\CrmClient;
use App\Services\Crm\LogCrmClient;
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
        //
    }
}
