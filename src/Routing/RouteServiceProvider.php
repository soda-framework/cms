<?php

namespace Soda\Cms\Routing;

use ReflectionClass;
use Illuminate\Support\Facades\Route;
use Soda\Cms\Http\Middleware\DraftAlert;
use Soda\Cms\Http\Middleware\HasRole;
use Soda\Cms\Http\Middleware\Drafting;
use Soda\Cms\Http\Middleware\ForceHttps;
use Soda\Cms\Http\Middleware\HasAbility;
use Soda\Cms\Http\Middleware\Authenticate;
use Soda\Cms\Http\Middleware\HasPermission;
use Soda\Cms\Http\Middleware\SluggableSession;
use Soda\Cms\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Soda\Cms\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $router = $this->app['router'];

        $router->middlewareGroup('soda.web', [Drafting::class]);
        $router->middlewareGroup('soda.api', [Drafting::class]);

        $router->pushMiddlewareToGroup('web', DraftAlert::class);
        if (config('soda.cms.https')) {
            $router->pushMiddlewareToGroup('soda.web', ForceHttps::class);
        }

        $router->aliasMiddleware('soda.auth', Authenticate::class);
        $router->aliasMiddleware('soda.guest', RedirectIfAuthenticated::class);

        $router->aliasMiddleware('soda.role', HasRole::class);
        $router->aliasMiddleware('soda.permission', HasPermission::class);
        $router->aliasMiddleware('soda.ability', HasAbility::class);


        $middlewareGroups = $router->getMiddlewareGroups();

        if (isset($middlewareGroups['web'])) {
            if (($key = array_search('Illuminate\Session\Middleware\StartSession', $middlewareGroups['web'])) !== false) {
                $middlewareGroups['web'][$key] = SluggableSession::class;
                array_unshift($router->middlewarePriority, SluggableSession::class);
            }

            $router->middlewareGroup('soda.sluggable-web', $middlewareGroups['web']);
        }

        parent::boot();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SluggableSession::class);
        //$this->overrideRouter();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
        //
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
        Route::group([
            'middleware' => ['soda.web'],
            'namespace'  => $this->namespace,
        ], function ($router) {
            require __DIR__.'/../../routes/web.php';
        });

        $this->app['router']->getRoutes()->refreshNameLookups();

        $this->app['events']->fire('soda.routing', $this->app->router);
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
        Route::group([
            'middleware' => ['api'],
            'namespace'  => $this->namespace,
            'prefix'     => config('soda.cms.path').'/api',
        ], function ($router) {
            require __DIR__.'/../../routes/api.php';
        });
    }

    protected function overrideRouter()
    {
        $coreRouter = $this->app['router'];

        // If the router is "rebound", we will need to rebuild the middleware.
        // by copying properties from the existing router instance

        $this->app->rebinding('router', function ($app, $router) use ($coreRouter) {
            $reflectionRouter = new ReflectionClass($coreRouter);
            $property = $reflectionRouter->getProperty('middlewareGroups');
            $property->setAccessible(true);
            $middlewareGroups = $property->getValue($coreRouter);

            $router->middlewarePriority = $coreRouter->middlewarePriority;

            foreach ($middlewareGroups as $key => $middleware) {
                $router->middlewareGroup($key, $middleware);
            }

            foreach ($coreRouter->getMiddleware() as $key => $middleware) {
                $router->middleware($key, $middleware);
            }

            $app->instance('routes', $router->getRoutes());
            \Route::clearResolvedInstance('router');
        });

        $this->app['router'] = $this->app->share(function ($app) {
            return new Router($app['events'], $app);
        });

        $this->app->alias('router', 'Illuminate\Contracts\Routing\Registrar');
        $this->app->alias('router', 'Illuminate\Routing\Router');
    }
}
