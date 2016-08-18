<?php
namespace Themes\SodaTheme\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;
use View;


class SodaThemeServiceProvider extends RouteServiceProvider {

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */

    protected $defer = false;

    protected $namespace = 'Themes\SodaTheme\Controllers';


    public function boot(Router $router) {
        parent::boot($router);

        $this->loadViewsFrom(__DIR__ . '/../../views', 'soda_theme_hint');
    }

    public function register() {
        $this->registerDependencies([
            'Themes\SodaTheme\Providers\EventsServiceProvider',
        ]);

        $this->publishes([__DIR__ . '/../../public' => public_path('soda_theme_hint')], 'public');
        $this->publishes([__DIR__ . '/../../config' => config_path()]);
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

    /**
     * Register dependies conditionally (e.g. dev dependencies)
     *
     * @param array $services
     */
    public function registerDependencies(array $services) {
        foreach ($services as $service) {
            $this->app->register($service);
        }
    }

    /**
     * @param array $facades
     */
    public function registerFacades(array $facades) {
        foreach ($facades as $facade => $class) {
            AliasLoader::getInstance()->alias($facade, $class);

        }
    }
}
