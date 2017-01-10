<?php

namespace Soda\Cms\Http\RequestMatcher\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;

class ControllerAction implements ActionInterface
{
    public function handle(Request $request, PageInterface $page, $parameters = [])
    {
        $namespace = $page->package;
        $controller = trim($page->getAttribute('view_action'), '\\');

        // Invalidate the current route
        Route::getCurrentRoute()->setUri('INVALID@ROUTE');

        // Create a route for our matched slug
        Route::match([$request->getMethod()], $page->slug, ['uses' => $namespace ? "$namespace\\$controller" : $controller])->middleware('web');

        // Dispatch, and match to our new route
        return Route::dispatch($request);
    }
}
