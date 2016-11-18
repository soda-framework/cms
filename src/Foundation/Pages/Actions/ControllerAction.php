<?php

namespace Soda\Cms\Foundation\Pages\Actions;

use App;
use Illuminate\Support\Facades\Route;
use Soda\Cms\Models\Page;

class ControllerAction implements ActionInterface
{
    public function handle(Page $page, $parameters = [])
    {
        $namespace = $page->package;
        $controller = trim($page->action, '\\');

        $currentRoute = Route::getCurrentRoute();
        Route::any($currentRoute->getUri(), ['uses' => $namespace ? "$namespace\\$controller" : $controller])->middleware('web');

        return Route::dispatch(app('request'));
    }
}
