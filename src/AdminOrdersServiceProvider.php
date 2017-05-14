<?php

namespace LaraMod\Admin\Orders;

use Illuminate\Support\ServiceProvider;
use LaraMod\Admin\Core\Traits\DashboardTrait;
use LaraMod\Admin\Orders\Controllers\OrdersController;

/**
 * Class AdminOrdersServiceProvider
 *
 * @package LaraMod\AdminOrders
 *
 * TODO: change payment methods to dynamic from config file
 */
class AdminOrdersServiceProvider extends ServiceProvider
{
    use DashboardTrait;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'adminorders');
        $this->publishes([
            __DIR__ . '/views' => base_path('resources/views/laramod/admin/orders'),
        ]);
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'migrations');

        /*
         * Add orders widget to dashboard
         */
        try{
            $this->addWidget($this->app->make(OrdersController::class)->ordersWidget());
        }catch (\Exception $e){
            $this->addWidget($e->getMessage());
        }


    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/routes.php';
    }
}
