<?php

namespace Soda\Cms\Foundation\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Soda\Cms\Http\Middleware\Authenticate;
use Soda\Cms\Http\Middleware\Drafting;
use Soda\Cms\Http\Middleware\Security;
use Soda\Cms\Http\Middleware\HasAbility;
use Soda\Cms\Http\Middleware\HasPermission;
use Soda\Cms\Http\Middleware\HasRole;
use Soda\Cms\Http\Middleware\RedirectIfAuthenticated;

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
        $this->app['router']->middlewareGroup('soda.web', [
            Drafting::class,
            Security::class,
        ]);

        $this->app['router']->middlewareGroup('soda.api', [
            Drafting::class,
        ]);

        $this->app['router']->middleware('soda.auth', Authenticate::class);
        $this->app['router']->middleware('soda.guest', RedirectIfAuthenticated::class);

        $this->app['router']->middleware('soda.role', HasRole::class);
        $this->app['router']->middleware('soda.permission', HasPermission::class);
        $this->app['router']->middleware('soda.ability', HasAbility::class);

        parent::boot();
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
            require(__DIR__.'/../../../routes/web.php');
        });

        $this->app->router->getRoutes()->refreshNameLookups();

        $this->app->events->fire('soda.routing', $this->app->router);
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
            require(__DIR__.'/../../../routes/api.php');
        });
    }
}
