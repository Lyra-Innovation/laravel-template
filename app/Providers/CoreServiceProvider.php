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

        $this->app->singleton(ConfigManager::class, function ($app) {
            return new ConfigManager();
        });

        $this->app->singleton(ViewComponent::class, function ($app) {
            return new ViewComponent();
        });

        $this->app->singleton(ActionManager::class, function ($app) {
            return new ActionManager();
        });

    }
}
