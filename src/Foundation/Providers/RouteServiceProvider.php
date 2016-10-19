<?php

namespace Soda\Cms\Foundation\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Laratrust\Middleware\LaratrustAbility;
use Laratrust\Middleware\LaratrustPermission;
use Laratrust\Middleware\LaratrustRole;
use Soda\Cms\Http\Middleware\Authenticate;
use Soda\Cms\Http\Middleware\Cms;

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
        $this->app['router']->middleware('role', LaratrustRole::class);
        $this->app['router']->middleware('permission', LaratrustPermission::class);
        $this->app['router']->middleware('ability', LaratrustAbility::class);

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
            require(__DIR__.'/../../../routes/web.php');
        });
    }
}
