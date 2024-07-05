<?php

namespace Narolalabs\ErrorLens;

use Illuminate\Support\ServiceProvider;
use Narolalabs\ErrorLens\Commands\AuthCommand;
use Narolalabs\ErrorLens\Commands\ErrorLensCommand;
use Narolalabs\ErrorLens\Commands\InstallPackage;
use Narolalabs\ErrorLens\Commands\UpdatePackage;
use Narolalabs\ErrorLens\Middleware\HttpBasicAuth;
use Narolalabs\ErrorLens\Middleware\AutoRemoveErrorLogs;
use Narolalabs\ErrorLens\Middleware\IsConfigSet;
use \Illuminate\Foundation\Application;
use \Narolalabs\ErrorLens\Exceptions\ErrorLensHandler;
use \Illuminate\Contracts\Debug\ExceptionHandler;
use \Illuminate\Foundation\Configuration\Exceptions;
use \Illuminate\Foundation\Exceptions\Handler;

class ErrorLensServiceProvider extends ServiceProvider
{
    private $laravelVersion = Application::VERSION;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish migration
        $this->publishes([
            __DIR__ . '/../database/migrations/create_error_lens_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_error_lens_table.php'),
        ], 'error-lens-migrations');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../resources/dist' => public_path('vendor/error-lens')
        ], 'error-lens-assets');

        // Publish config
        $this->publishes([
            __DIR__ . '/../config' => config_path(),
        ], 'error-lens-config');

        // publish seeder using command
        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'error-lens-seeds');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'error-lens');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../config/masked-keywords.php', 'masked-keywords');
    }

    public function register()
    {
        // Call the parent register method
        parent::register();

        // Register error handler
        $this->registerErrorLensHandler();

        // Register your middleware
        $this->app['router']->aliasMiddleware('basicAuth', HttpBasicAuth::class);
        $this->app['router']->aliasMiddleware('isConfigSet', IsConfigSet::class);
        $this->app['router']->aliasMiddleware('autoRemoveErrorLogs', AutoRemoveErrorLogs::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ErrorLensCommand::class,
                AuthCommand::class,
                UpdatePackage::class,
                InstallPackage::class,
            ]);
        }
    }

    private function registerErrorLensHandler()
    {
        // Register error handler
        if ((int) $this->laravelVersion >= 11) {
            $errorLensHandler = app(ErrorLensHandler::class);

            $this->app->singleton(ExceptionHandler::class, Handler::class);

            $using = function (Exceptions $exceptions) use ($errorLensHandler) {
                $exceptions->render(function (\Throwable $exception, $request) use ($errorLensHandler) {
                    return $errorLensHandler->render($request, $exception);
                });
            };

            $this->app->afterResolving(
                Handler::class,
                fn($handler) => $using(new Exceptions($handler)),
            );
        } else {
            $this->app->singleton(ExceptionHandler::class, ErrorLensHandler::class);
        }
    }
}
