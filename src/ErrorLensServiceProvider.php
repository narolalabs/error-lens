<?php

namespace Narolalabs\ErrorLens;

use Narolalabs\ErrorLens\Commands\AuthCommand;
use Narolalabs\ErrorLens\Commands\ErrorLensCommand;
use Narolalabs\ErrorLens\Middleware\HttpBasicAuth;
use Illuminate\Pagination\Paginator;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ErrorLensServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('error-lens')
            ->hasConfigFile('error-lens')
            ->hasViews('error-lens')
            ->hasAssets()
            ->hasRoute('web')
            ->hasMigration('create_error_lens_table');
    }

    public function boot()
    {
        parent::boot();

        Paginator::useBootstrap();
    }

    public function register()
    {
         // Call the parent register method
         parent::register();

        // Register your middleware
        $this->app['router']->aliasMiddleware('basicAuth', HttpBasicAuth::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ErrorLensCommand::class,
                AuthCommand::class,
            ]);

            // publish seeder using command
            $this->publishes([
                __DIR__.'/../database/seeders' => database_path('seeders'),
            ], 'error-lens-seeds');
        }
    }
}
