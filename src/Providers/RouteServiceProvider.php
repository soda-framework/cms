<?php

namespace Soda\Cms\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Soda\Cms\Http\Middleware\HasAbility;
use Soda\Cms\Http\Middleware\HasPermission;
use Soda\Cms\Http\Middleware\HasRole;
use Soda\Cms\Http\Middleware\Authenticate;
use Soda\Cms\Http\Middleware\Cms;
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
        $this->app['router']->middleware('soda.main', Cms::class);
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
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require(__DIR__.'/../../routes/web.php');
        });

        $router->getRoutes()->refreshNameLookups();

        $this->app->events->fire('soda.routing', $this->app->router);
    }
}
