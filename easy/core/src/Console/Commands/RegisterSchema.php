<?php

namespace Easy\Core\Console\Commands;

use Illuminate\Console\Command;

/**
 * @RegisterSchema
 */
class RegisterSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy:add-schema {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add schema';

    /**
     * @return void
     */
    public function handle()
    {
        $this->addSchemaToGQL(trim($this->option('path')));
        $this->info('Schema file updated successfully.');
    }

    /**
     * @return void
     */
    public function addSchemaToGQL(string $path)
    {
        $prepend = '#import ../package/' . $path . "\n";
        $file = base_path('graphql/schema.graphql');
        $fileContents = file_get_contents($file);
        file_put_contents($file, $prepend . $fileContents);
    }
}
