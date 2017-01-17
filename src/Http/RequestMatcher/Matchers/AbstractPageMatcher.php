<?php

namespace Soda\Cms\Http\RequestMatcher\Matchers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\Registrar as RouterContract;

abstract class AbstractPageMatcher implements MatcherInterface
{
    protected $router;

    public function __construct(RouterContract $router)
    {
        $this->router = $router;
    }

    abstract public function match($slug);

    abstract public function render(Request $request);

    protected function dispatchSluggedRoute(Request $request, $slug, $handle)
    {
        // Invalidate the current route
        $this->router->getCurrentRoute()->setUri('INVALID@ROUTE');

        // Create a route for our matched slug
        $this->router->match([$request->getMethod()], $slug, $handle)->middleware('web');

        // Dispatch, and match to our new route
        return $this->router->dispatch($request);
    }
}
