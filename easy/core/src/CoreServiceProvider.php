<?php

namespace Easy\Core;

use Easy\Core\Console\Commands\InstallCore;
use Easy\Core\Console\Commands\RegisterSchema;
use Easy\Core\Support\EasyServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;

/**
 * @CoreServiceProvider
 */
class CoreServiceProvider extends EasyServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/cors.php', 'cors');
        $this->mergeConfigFileFrom(__DIR__ . '/config/graphql-playground.php', 'graphql-playground');
        $this->mergeConfigFileFrom(__DIR__ . '/config/lighthouse.php', 'lighthouse');
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->registerPreferences();
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->registerCommands();
            $this->configurePublishing();
        }
    }

    /**
     * @return void
     */
    protected function registerPreferences()
    {
        if (file_exists(config_path('preference.php'))) {
            $replace = config('preference');
            if (is_array($replace) && sizeof($replace)) {
                foreach ($replace as $key => $data) {
                    $this->app->singleton($data['source'], $data['destination']);
                }
            }
        }
    }

    /**
     * @return void
     */
    private function registerCommands()
    {
        $this->commands([
            InstallCore::class,
            RegisterSchema::class
        ]);
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        $this->publishes([
            __DIR__ . '/../stubs/graphql' => base_path('graphql'),
            __DIR__ . '/../stubs/config/preference.php' => config_path('preference.php'),
            __DIR__ . '/../stubs/config/schema.php' => config_path('schema.php'),
            __DIR__ . '/../stubs/resources/views' => resource_path('views')
        ], 'easy-core');
    }
}
