<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DataManager::class, function ($app) {
            return new DataManager();
        });

        $this->app->singleton(ViewComponent::class, function ($app) {
            return new ViewComponent();
        });

    }
}
