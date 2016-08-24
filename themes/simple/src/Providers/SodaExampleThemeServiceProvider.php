<?php
namespace Themes\SodaExample\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;
use View;


class SodaExampleThemeServiceProvider extends RouteServiceProvider {

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */

    protected $defer = false;

    protected $namespace = 'Themes\SodaExample\Controllers';


    public function boot(Router $router) {
        parent::boot($router);

        $this->loadViewsFrom(__DIR__ . '/../../views', 'soda-example');
    }

    public function register() {
        $this->publishes([__DIR__ . '/../../public' => public_path('soda-example')], 'public');
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router) {
        $router->group(['namespace' => $this->namespace, 'middleware' => 'web'], function ($router) {
            require_once __DIR__ . '/../routes.php';
        });
    }
}
