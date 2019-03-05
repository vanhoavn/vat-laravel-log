<?php

namespace VZT\Laravel\VZTLog;

use Illuminate\Support\ServiceProvider;
use VZT\Laravel\VZTLog\Logic\VZTLogLogic;

class VZTLogModuleProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // $configPath = __DIR__ . '/config/v_z_t_log.php';
        // $this->mergeConfigFrom($configPath, 'v_z_t_log');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/databases/migrations');
        }

        $this->app->singleton(VZTLogLogic::class);

        // if ($this->app->runningInConsole()) {
        //     $this->commands([
        //     ]);
        // }

        // BEGIN - REPOSITORIES REGISTRY
        // END - REPOSITORIES REGISTRY
    }
}
