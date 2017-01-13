<?php namespace Soda\Cms\Foundation\Routing;

use Illuminate\Routing\RouteCollection as IlluminateRouteCollection;

class RouteCollection extends IlluminateRouteCollection
{
    /**
     * Add the given route to the arrays of routes.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @return void
     */
    protected function addToCollections($route)
    {
        $domainAndUri = $route->domain().$route->getUri().$route->getPriority();

        foreach ($route->methods() as $method) {
            $this->routes[$method][$domainAndUri] = $route;
        }

        $this->allRoutes[$method.$domainAndUri] = $route;
    }

    /**
     * Order routes by priority number
     *
     * @return void
     */
    public function buildRoutesOrder()
    {
        $comparePriority = function ($r1, $r2) {
            $a = $r1->getPriority();
            $b = $r2->getPriority();
            if ($a == $b) {
                return 0;
            }

            return ($a < $b) ? 1 : -1;
        };

        uasort($this->allRoutes, $comparePriority);
        foreach (array_keys($this->routes) as $method) {
            uasort($this->routes[$method], $comparePriority);
        }

        return $this;
    }
}
