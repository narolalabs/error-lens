<?php

namespace Narolalabs\ErrorLens\Commands;

use Illuminate\Console\Command;
use \Illuminate\Support\Facades\File;

class InstallPackage extends Command
{
    public $signature = 'error-lens:install';

    public $description = 'Install the necessary things with single command';

    public function handle(): bool
    {
        $noInteraction = app()->environment('production') ? '--no-interaction' : '';

        // Publish migration
        $this->call('vendor:publish', ['--tag' => 'error-lens-migrations']);

        // Run migration
        if ($latestMigration = $this->getLatestMigration()) {
            $this->call('migrate', [
                '--path' => '/database/migrations/' . $latestMigration,
                '--force' => true,
                $noInteraction
            ]);
        }

        // Publish latest assets
        $this->call('vendor:publish', ['--tag' => 'error-lens-assets', '--force' => true]);

        // Publish latest seeder
        $this->call('vendor:publish', ['--tag' => 'error-lens-seeds', '--force' => true]);

        // Run seeder
        $this->call('db:seed', [
            '--class' => 'ErrorLensConfigurationSeeder',
            '--force' => true,
            $noInteraction
        ]);

        // Clear the cache
        $this->call('config:clear');

        return true;
    }

    private function getLatestMigration()
    {
        // Read all migration files
        $allMigrationFiles = \File::allFiles(database_path('migrations'));

        // Find latest migration of error-lens which is generated on current day
        $allErrorLensMigrationFiles = array_filter($allMigrationFiles, function ($allMigrationFile) {
            return strpos($allMigrationFile->getFilename(), 'create_error_lens') !== false
                && strpos($allMigrationFile->getFilename(), date('Y_m_d')) !== false;
        });

        // return end($allErrorLensMigrationFiles) ? rtrim(end($allErrorLensMigrationFiles)->getFilename(), '.php') : '';
        return end($allErrorLensMigrationFiles) ? end($allErrorLensMigrationFiles)->getFilename() : '';
    }
}
