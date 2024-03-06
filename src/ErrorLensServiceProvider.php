<?php

namespace Narolalabs\ErrorLens;

use Illuminate\Support\ServiceProvider;
use Narolalabs\ErrorLens\Commands\AuthCommand;
use Narolalabs\ErrorLens\Commands\ErrorLensCommand;
use Narolalabs\ErrorLens\Commands\UpdatePackage;
use Narolalabs\ErrorLens\Middleware\HttpBasicAuth;
use Narolalabs\ErrorLens\Middleware\AutoRemoveErrorLogs;
use Illuminate\Pagination\Paginator;
use Narolalabs\ErrorLens\Middleware\IsConfigSet;

class ErrorLensServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish migration
        $this->publishes([
            __DIR__.'/../database/migrations/create_error_lens_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_error_lens_table.php'),
        ], 'error-lens-migrations');

        // Publish assets
        $this->publishes([
            __DIR__.'/../resources/dist' => public_path('vendor/error-lens')
        ], 'error-lens-assets');

        // Publish view
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/error-lens'),
        ], 'error-lens-views');

        // Publish config
        $this->publishes([
            __DIR__.'/../config' => config_path(),
        ], 'error-lens-config');

        // publish seeder using command
        $this->publishes([
            __DIR__.'/../database/seeders' => database_path('seeders'),
        ], 'error-lens-seeds');
        
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'error-lens');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
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
                UpdatePackage::class,
            ]);
        }
    }
}
