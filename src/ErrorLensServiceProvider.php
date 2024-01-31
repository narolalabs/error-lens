<?php

namespace Narolalabs\ErrorLens;

use Narolalabs\ErrorLens\Commands\AuthCommand;
use Narolalabs\ErrorLens\Commands\ErrorLensCommand;
use Narolalabs\ErrorLens\Middleware\HttpBasicAuth;
use Narolalabs\ErrorLens\Middleware\AutoRemoveErrorLogs;
use Illuminate\Pagination\Paginator;
use Narolalabs\ErrorLens\Middleware\IsConfigSet;
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
        $this->app['router']->aliasMiddleware('isConfigSet', IsConfigSet::class);
        $this->app['router']->aliasMiddleware('autoRemoveErrorLogs', AutoRemoveErrorLogs::class);

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
