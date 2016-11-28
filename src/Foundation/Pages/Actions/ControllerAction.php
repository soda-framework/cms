<?php

namespace Soda\Cms\Foundation\Pages\Actions;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Soda\Cms\Models\Page;

class ControllerAction implements ActionInterface
{
    public function handle(Request $request, Page $page, $parameters = [])
    {
        $namespace = $page->package;
        $controller = trim($page->action, '\\');

        // Invalidate the current route
        Route::getCurrentRoute()->setUri('INVALID@ROUTE');

        // Create a route for our matched slug
        Route::match([$request->getMethod()], $page->slug, ['uses' => $namespace ? "$namespace\\$controller" : $controller])->middleware('web');

        // Dispatch, and match to our new route
        return Route::dispatch($request);
    }
}
