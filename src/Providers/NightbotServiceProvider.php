<?php

namespace Androidbeet\Nightbot\Providers;

use Androidbeet\Nightbot\Nightbot;
use Illuminate\Support\ServiceProvider;

class NightbotServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/langs', 'nightbot');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nightbot');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/nightbot.php' => config_path('nightbot.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/nightbot'),
            ], 'public');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/nightbot'),
            ], 'views');

//            $this->publishes([
//                __DIR__.'/../resources/assets' => public_path('vendor/nightbot'),
//            ], 'assets');

            $this->publishes([
                __DIR__.'/../resources/langs' => resource_path('lang/vendor/nightbot'),
            ], 'lang');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'migrations');

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/nightbot.php', 'nightbot');

        $this->app->singleton(Nightbot::class, function () {
            return new Nightbot;
        });
    }
}
