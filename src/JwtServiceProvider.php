<?php

namespace CyberLama\JwtAuth;

use Illuminate\Support\ServiceProvider;

class JwtServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('JwtService', function () {
            return new JwtService();
        });
    }
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
