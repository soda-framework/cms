<?php

namespace Soda\Cms\Routing;

use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router as IlluminateRouter;

class Router extends IlluminateRouter
{
    /**
     * @var string
     */
    const DEFAULT_PRIORITY = 50;
    /**
     * All of the verbs supported by the router.
     *
     * @var array
     */
    public static $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];
    /**
     * The priority-sorted list of middleware.
     *
     * Forces the listed middleware to always be in the given order.
     *
     * @var array
     */
    public $middlewarePriority = [];
    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;
    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;
    /**
     * The route collection instance.
     *
     * @var RouteCollection
     */
    protected $routes;
    /**
     * The currently dispatched route instance.
     *
     * @var Route
     */
    protected $current;
    /**
     * The request currently being dispatched.
     *
     * @var \Illuminate\Http\Request
     */
    protected $currentRequest;
    /**
     * All of the short-hand keys for middlewares.
     *
     * @var array
     */
    protected $middleware = [];
    /**
     * All of the middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [];
    /**
     * The registered route value binders.buildRoutesOrder.
     *
     * @var array
     */
    protected $binders = [];
    /**
     * The globally available parameter patterns.
     *
     * @var array
     */
    protected $patterns = [];
    /**
     * The route group attribute stack.
     *
     * @var array
     */
    protected $groupStack = [];

    /**
     * Create a new Router instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     * @param  \Illuminate\Container\Container         $container
     */
    public function __construct(Dispatcher $events, Container $container = null)
    {
        $this->events = $events;
        $this->routes = new RouteCollection;
        $this->container = $container ?: new Container;

        $this->bind('_missing', function ($v) {
            return explode('/', $v);
        });
    }

    /**
     * Dispatch the request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function dispatch(Request $request)
    {
        $this->getRoutes();

        return parent::dispatch($request);
    }

    /**
     * Get the underlying route collection.
     *
     * @return RouteCollection
     */
    public function getRoutes()
    {
        return parent::getRoutes()->buildRoutesOrder();
    }

    /**
     * Create a new Route object.
     *
     * @param  array|string $methods
     * @param  string       $uri
     * @param  mixed        $action
     *
     * @return Route
     */
    protected function newRoute($methods, $uri, $action)
    {
        $route = (new Route($methods, $uri, $action))->setContainer($this->container);
        $priority = self::DEFAULT_PRIORITY - $this->routes->count();
        $route->setPriority($priority);

        return $route;
    }

    /**
     * Create a new route instance.
     *
     * @param  array|string $methods
     * @param  string       $uri
     * @param  mixed        $action
     *
     * @return Route
     */
    protected function createRoute($methods, $uri, $action)
    {
        $route = parent::createRoute($methods, $uri, $action);
        if (! empty($this->groupStack)) {
            $route = $this->mergePriority($route);
        }

        return $route;
    }

    /**
     * @param Route $route
     *
     * @return Route
     */
    protected function mergePriority(Route $route)
    {
        $row = last($this->groupStack);
        if (isset($row['priority'])) {
            $route->setPriority($row['priority']);
        }

        return $route;
    }
}
