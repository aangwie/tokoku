<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Events\OrderStatusChanged;
use App\Listeners\SendOrderStatusNotification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event-listener mappings
        Event::listen(
            OrderStatusChanged::class,
            SendOrderStatusNotification::class,
        );
    }
}
