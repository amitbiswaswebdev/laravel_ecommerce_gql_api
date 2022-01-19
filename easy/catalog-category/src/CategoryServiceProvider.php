<?php

namespace Easy\Category;

use Easy\Core\Support\EasyServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;

class CategoryServiceProvider extends EasyServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFileFrom(__DIR__ . '/config/schema.php', 'schema', true);
        $this->mergeConfigFileFrom(__DIR__ . '/config/preference.php', 'preference', true);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->configurePublishing();
        }
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations')
        ], 'easy-category');
    }
}
