<?php
namespace Themes\SodaTheme\Providers;

use Exception;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;
use Symfony\Component\Debug\Exception\FatalErrorException;
use View;
use Themes\SodaTheme\Components\SodaHelperClass;


class SodaThemeServiceProvider extends RouteServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */

    protected $defer = false;

    protected $namespace = 'Themes\SodaTheme\Controllers';

    //public $listen = [
    //    'Soda\Events\NavigationWasRendered' => [
    //        'Themes\Defaulttheme\Listeners\AddNavLinks',
    //    ],
    //];


    public function boot(Router $router)
    {
        parent::boot($router);

        $this->loadViewsFrom(__DIR__ . '/../../views', 'soda_theme_hint');
    }

    public function register()
    {

        $this->app->singleton('soda_helper_class', function ($app) {
            return new SodaHelperClass($app);
        });


        $this->registerDependencies([
            'Themes\SodaTheme\Providers\EventsServiceProvider',
            'Themes\SodaTheme\Providers\AuthServiceProvider',
        ]);

        $this->registerFacades([
            'SodaHelperClass'    => 'Themes\SodaTheme\Facades\SodaHelperClassFacade'
        ]);

        $this->publishes([__DIR__.'/../../public' => public_path('soda_theme_hint')], 'public');
        $this->publishes([__DIR__.'/../../config' => config_path()]);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace, 'middleware' => 'web'], function ($router) {
            require_once __DIR__ . '/../routes.php';
        });
    }

    /**
     * Register dependies conditionally (e.g. dev dependencies)
     *
     * @param array $services
     */
    public function registerDependencies(array $services)
    {
        foreach ($services as $service) {
            $this->app->register($service);
        }
    }

    /**
     * @param array $facades
     */
    public function registerFacades(array $facades)
    {
        foreach ($facades as $facade => $class) {
            AliasLoader::getInstance()->alias($facade, $class);

        }
    }
}