<?php

declare(strict_types=1);

namespace Easy\User;

use Easy\Core\Support\EasyServiceProvider;

/**
 * @UserServiceProvider
 */
class UserServiceProvider extends EasyServiceProvider
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
        //
    }
}
