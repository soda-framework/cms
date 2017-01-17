<?php

namespace Soda\Cms\Components\Pages\Actions;

use App;
use Soda\Cms\Models\Page;
use Soda\Cms\Components\Pages\ActionTypeInterface;

class ControllerAction implements ActionTypeInterface
{
    public function handle(Page $page, $parameters = [])
    {
        $namespace = $page->package;
        $controller = trim($page->action, '\\');

        return App::call($namespace ? "$namespace\\$controller" : $controller);
    }
}
