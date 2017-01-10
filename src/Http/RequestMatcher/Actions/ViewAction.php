<?php

namespace Soda\Cms\Http\RequestMatcher\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;

class ViewAction implements ActionInterface
{
    public function handle(Request $request, PageInterface $page, $parameters = [])
    {
        $view = $page->getAttribute('view_action');
        $view_params = array_merge(compact('page'), $parameters);

        // Invalidate the current route
        Route::getCurrentRoute()->setUri('INVALID@ROUTE');

        // Create a route for our matched slug
        Route::match([$request->getMethod()], $page->slug, function() use ($view, $view_params) {
            return view($view, $view_params);
        })->middleware('web');

        // Dispatch, and match to our new route
        return Route::dispatch($request);
    }
}
