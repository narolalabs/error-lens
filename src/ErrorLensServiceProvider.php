<?php

namespace Narolalabs\ErrorLens;

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
            // ->hasConfigFile()
            ->hasViews('error-lens')
            ->hasAssets()
            ->hasRoute('web')
            ->hasMigration('create_error_lens_table');
    }

    public function boot()
    {
        parent::boot();

        Paginator::useBootstrapFive();
    }
}
