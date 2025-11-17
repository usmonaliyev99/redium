<?php

namespace Usmonaliyev\Redium;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Usmonaliyev\Redium\Auth\Guard;

class RediumServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config/redium.php' => config_path('redium.php')], 'redium-config');
        }

        Auth::extend('redium', function (Application $app, string $name, array $config) {
            return new Guard(Auth::createUserProvider($config['provider']), $app->make('request'));
        });
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/redium.php', 'redium');
    }
}
