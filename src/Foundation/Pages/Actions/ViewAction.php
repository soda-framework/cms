<?php

namespace Soda\Cms\Foundation\Pages\Actions;

use Soda\Cms\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ViewAction implements ActionInterface
{
    public function handle(Request $request, Page $page, $parameters = [])
    {
        $view = ($page->package && ! str_contains($page->action, '::') ? $page->package.'::' : '').$page->action;
        $view_params = array_merge(compact('page'), $parameters);

        // Invalidate the current route
        Route::getCurrentRoute()->setUri('INVALID@ROUTE');

        // Create a route for our matched slug
        Route::match([$request->getMethod()], $page->slug, function () use ($view, $view_params) {
            return view($view, $view_params);
        })->middleware('web');

        // Dispatch, and match to our new route
        return Route::dispatch($request);
    }
}
