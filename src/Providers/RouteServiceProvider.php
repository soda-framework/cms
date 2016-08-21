<?php

namespace Soda\Cms\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Soda\Cms\Middleware\Authenticate;
use Soda\Cms\Middleware\Cms;
use Zizaco\Entrust\Middleware\EntrustAbility;
use Zizaco\Entrust\Middleware\EntrustPermission;
use Zizaco\Entrust\Middleware\EntrustRole;

class RouteServiceProvider extends ServiceProvider {
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Soda\Cms\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router) {
        $router->middleware('soda.main', Cms::class);
        $router->middleware('soda.auth', Authenticate::class);
        $router->middleware('role', EntrustRole::class);
        $router->middleware('permission', EntrustPermission::class);
        $router->middleware('ability', EntrustAbility::class);

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router) {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require(__DIR__ . '/../routes.php');
        });
    }
}
