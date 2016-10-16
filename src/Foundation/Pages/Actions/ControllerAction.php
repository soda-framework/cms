<?php

namespace Soda\Cms\Foundation\Pages\Actions;

use App;
use Soda\Cms\Models\Page;

class ControllerAction implements ActionInterface
{
    public function handle(Page $page, $parameters = [])
    {
        $namespace = $page->package;
        $controller = trim($page->action, '\\');

        return App::call($namespace ? "$namespace\\$controller" : $controller);
    }
}
