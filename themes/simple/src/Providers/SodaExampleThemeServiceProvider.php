<?php

namespace Themes\SodaExample\Providers;

use Illuminate\Routing\Router;
use Themes\SodaExample\Handlers\ExceptionHandler;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler as BaseExceptionHandler;

class SodaExampleThemeServiceProvider extends RouteServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $defer = false;
    protected $handlesErrors = false;

    protected $namespace = 'Themes\SodaExample\Controllers';

    public function boot(Router $router)
    {
        parent::boot($router);

        $this->loadViewsFrom(__DIR__.'/../../views', 'soda-example');
    }

    public function register()
    {
        $this->publishes([__DIR__.'/../../public' => public_path('themes/soda-example')], 'public');

        if ($this->handlesErrors) {
            $this->app->singleton(BaseExceptionHandler::class, ExceptionHandler::class);
        }
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace, 'middleware' => 'web'], function ($router) {
            require_once __DIR__.'/../routes.php';
        });
    }
}
