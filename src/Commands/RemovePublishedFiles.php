<?php

namespace Narolalabs\ErrorLens\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RemovePublishedFiles extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    public $signature = 'error-lens:remove-published-files';

    public $description = 'Remove vendor published files';

    public function handle(): int
    {
        // Define the paths to the files or directories you want to delete
        $paths = [
            public_path('vendor/error-lens'),
            config_path('error-lens.php'),
            resource_path('views/vendor/error-lens'),
            database_path('seeders/ErrorLensConfigurationSeeder.php'),
        ];

        // Loop through each path and delete it
        foreach ($paths as $path) {
        $this->removeFile($path);
        }

        // Remove migration files
        $errorLensMigrations = $this->getAllErrorLensMigrations();
        if ($errorLensMigrations && is_array($errorLensMigrations) && count($errorLensMigrations)) {
            foreach ($errorLensMigrations as $errorLensMigration) {
                $this->removeFile(database_path('migrations/'.$errorLensMigration));
            }
        }

        return 0;
    }

    private function getAllErrorLensMigrations()
    {
        // Read all migration files
        $allMigrationFiles = \File::allFiles(database_path('migrations'));

        // Find latest migration of error-lens which is generated on current day
        $allErrorLensMigrationFiles = array_filter($allMigrationFiles, function ($allMigrationFile) {
            return strpos($allMigrationFile->getFilename(), 'create_error_lens') !== false
                && strpos($allMigrationFile->getFilename(), date('Y_m_d')) !== false;
        });

        $allErrorLensMigrationFiles = array_map(function ($allMigrationFile) {
            return $allMigrationFile->getFilename();
        }, $allErrorLensMigrationFiles);

        return $allErrorLensMigrationFiles;
    }

    private function removeFile($path)
    {
        try {
            if (File::isDirectory($path)) {
                File::deleteDirectory($path);
                $this->info("Deleted directory: $path");
            } else if (File::exists($path)) {
                File::delete($path);
                $this->info("Deleted: $path");
            } else {
                $this->info("Path does not exist: $path");
            }
        } catch (\Throwable $e) {
        }
    }
}
