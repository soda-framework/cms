<?php

namespace Soda\Cms\Http\Matcher\Actions;

use App;
use Soda\Cms\Foundation\Pages\Interfaces\PageInterface;

class ControllerAction implements ActionInterface
{
    public function handle(PageInterface $page, $parameters = [])
    {
        $namespace = $page->package;
        $controller = trim($page->action, '\\');

        return App::call($namespace ? "$namespace\\$controller" : $controller);
    }
}
