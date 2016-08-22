<?php
namespace Themes\SodaExample\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;
use Themes\SodaExample\Components\SodaExampleInstance;
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
        $this->app->singleton('soda-example', function ($app) {
            return new SodaExampleInstance($app);
        });

        $this->registerDependencies([
            'Themes\SodaExample\Providers\EventsServiceProvider',
            'Themes\SodaExample\Providers\AuthServiceProvider',
        ]);

        $this->registerFacades([
            'SodaExample' => 'Themes\SodaExample\Facades\SodaExampleFacade',
        ]);

        $this->publishes([__DIR__ . '/../../public' => public_path('soda-example')], 'public');
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
