<?php namespace Soda\Cms\Foundation\Routing;

use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Router as IlluminateRouter;

class Router extends IlluminateRouter
{
    /**
     * @var string
     */
    const DEFAULT_PRIORITY = 50;

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
     * Create a new Route object.
     *
     * @param  array|string $methods
     * @param  string       $uri
     * @param  mixed        $action
     *
     * @return \Illuminate\Routing\Route
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
     * @return \Illuminate\Routing\Route
     */
    protected function createRoute($methods, $uri, $action)
    {
        $route = parent::createRoute($methods, $uri, $action);
        if (!empty($this->groupStack)) {
            $route = $this->mergePriority($route);
        }

        return $route;
    }

    protected function mergePriority($route)
    {
        $row = last($this->groupStack);
        if (isset($row['priority'])) {
            $route->setPriority($row['priority']);
        }

        return $route;
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
     * @return \Illuminate\Routing\RouteCollection
     */
    public function getRoutes()
    {
        return parent::getRoutes()->buildRoutesOrder();
    }
}
