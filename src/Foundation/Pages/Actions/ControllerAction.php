<?php

namespace Soda\Cms\Foundation\Pages\Actions;

use App;
use Illuminate\Support\Facades\Route as RouteFacade;
use Soda\Cms\Models\Page;

class ControllerAction implements ActionInterface
{
    public function handle(Page $page, $parameters = [])
    {
        $namespace = $page->package;
        $controller = trim($page->action, '\\');

        $currentRoute = RouteFacade::getCurrentRoute();
        RouteFacade::any($currentRoute->getUri(), ['uses' => $namespace ? "$namespace\\$controller" : $controller]);

        return RouteFacade::dispatch(app('request'));
    }
}
