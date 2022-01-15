<?php

namespace Easy\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Artisan;

/**
 * @InstallCore
 */
class InstallCore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bootstrapping the project';

    /**
     * @return void
     */
    public function handle()
    {
        $this->requireComposerPackages(
            'laravel/sanctum:^2.6',
            'nuwave/lighthouse:^5.35',
            'intervention/image:^2.7.0',
            'mll-lab/laravel-graphql-playground:^2.5'
        );
        $this->info('Dependency gathered.');

        // publish config files
        (new Filesystem)->copy(__DIR__ . '/../../../stubs/config/preference.php', config_path('preference.php'));
        (new Filesystem)->copy(__DIR__ . '/../../../stubs/config/schema.php', config_path('schema.php'));
        $this->info('config file published');

        (new Filesystem)->ensureDirectoryExists(resource_path('views/vendor/graphql-playground'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/resources/views', resource_path('views'));
        $this->info('Views published');

        Artisan::call('vendor:publish --tag=lighthouse-config --tag=graphql-playground-config');
        $this->info('lighthouse and graphql-playground config published');

        $this->registerAllSchemaToGQL();
        $this->info('All schemas registered and published.');

        $this->info('Project scaffolding installed successfully.');
    }

    /**
     * Installs the given Composer Packages into the application.
     *
     * @param mixed $packages
     * @return void
     */
    protected function requireComposerPackages($packages)
    {
        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );
        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    /**
     * @return void
     */
    public function registerAllSchemaToGQL()
    {
        (new Filesystem)->ensureDirectoryExists(base_path('graphql'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../../stubs/graphql', base_path('graphql'));
        $prepend = '';
        $schema = config('schema');
        if (is_array($schema) && sizeof($schema)) {
            foreach ($schema as $key => $data) {
                $path = (array_key_exists('path', $data)) ? $data['path'] : 'schema.graphql';
                $prepend = $prepend . '#import ../package/' . $data['module'] . '/src/gql/' . $path . "\n";
            }
        }
        $file = base_path('graphql/schema.graphql');
        $fileContents = file_get_contents($file);
        file_put_contents($file, $prepend . $fileContents);
    }
}
