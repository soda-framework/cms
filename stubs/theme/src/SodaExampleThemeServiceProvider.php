<?php

namespace Themes\SodaExample;

use Soda\Cms\Support\ThemeExceptionHandler;
use Illuminate\Contracts\Debug\ExceptionHandler as BaseExceptionHandler;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class SodaExampleThemeServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $defer = false;

    protected $handlesErrors = false;

    protected $namespace = 'Themes\SodaExample\Http\Controllers';

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'soda-example');
        $this->publishes([__DIR__.'/../public' => public_path('themes/soda-example')], 'soda-example.public');
        $this->publishes([__DIR__.'/../config' => config_path('themes/soda-example')], 'soda-example.config');

        $this->app->config->set('auth.providers.soda-example', $this->app->config->get('themes.soda-example.auth.provider'));
        $this->app->config->set('auth.guards.soda-example', $this->app->config->get('themes.soda-example.auth.guard'));
        $this->app->config->set('auth.passwords.soda-example', $this->app->config->get('themes.soda-example.auth.password'));

        parent::boot();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/auth.php', 'themes.soda-example.auth');

        if ($this->handlesErrors) {
            $this->bindErrorHandler();
        }
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $this->app['router']->group([
            'middleware' => 'web',
            'namespace'  => $this->namespace,
        ], function ($router) {
            require_once __DIR__.'/../routes/web.php';
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $this->app['router']->group([
            'middleware' => 'api',
            'namespace'  => $this->namespace,
            'prefix'     => 'api',
        ], function ($router) {
            require_once __DIR__.'/../routes/api.php';
        });
    }

    public function bindErrorHandler()
    {
        $this->app->singleton(BaseExceptionHandler::class, function ($app) {
            return (new ThemeExceptionHandler)->setTheme('soda-example');
        });

        return $this;
    }
}
