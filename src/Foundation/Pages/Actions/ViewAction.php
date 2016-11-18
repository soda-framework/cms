<?php

namespace Soda\Cms\Foundation\Pages\Actions;

use Illuminate\Support\Facades\Route;
use Soda\Cms\Models\Page;

class ViewAction implements ActionInterface
{
    public function handle(Page $page, $parameters = [])
    {
        $view = ($page->package && !str_contains($page->action, '::') ? $page->package.'::' : '').$page->action;
        $view_params = array_merge(compact('page'), $parameters);

        $currentRoute = Route::getCurrentRoute();
        Route::any($currentRoute->getUri(), function() use ($view, $view_params) {
            return view($view, $view_params);
        })->middleware('web');

        return Route::dispatch(app('request'));
    }
}
