<?php

namespace LaraMod\AdminOrders;

use Illuminate\Support\ServiceProvider;

class AdminOrdersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'adminorders');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/laramod/admin-orders'),
        ]);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
    }
}