<?php

namespace Leaf\LaraFlash;

use Illuminate\Support\ServiceProvider;

class FlashServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
       $this->app->singleton('toastr', function ($app) {
            return new Toastr($app['session'], $app['config']);
       });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'Toastr');

        $this->publishes([
            __DIR__ . '/views' => base_path('resources/views/vender/toastr'),
            __DIR__ . '/config/toastr.php' => config_path('toastr.php'),
        ]);
    }

    public function provides()
    {
        return ['toastr'];
    }
}
