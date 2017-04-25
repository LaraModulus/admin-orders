<?php

namespace LaraMod\Admin\Orders;

use Illuminate\Support\ServiceProvider;

/**
 * Class AdminOrdersServiceProvider
 *
 * @package LaraMod\AdminOrders
 *
 * TODO: change payment methods to dynamic from config file
 */
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
            __DIR__.'/views' => base_path('resources/views/laramod/admin/orders'),
        ]);
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

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
