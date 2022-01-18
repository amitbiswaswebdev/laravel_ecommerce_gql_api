<?php

namespace Easy\Category;

use Easy\Core\Support\EasyServiceProvider;

class CategoryServiceProvider extends EasyServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
//        $this->mergeConfigFileFrom(__DIR__ . '/config/schema.php', 'schema', true);
//        $this->mergeConfigFileFrom(__DIR__ . '/config/preference.php', 'preference', true);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
