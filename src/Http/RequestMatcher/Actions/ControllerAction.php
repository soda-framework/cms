<?php

namespace Soda\Cms\Http\RequestMatcher\Actions;

use Soda\Cms\Database\Pages\Interfaces\PageInterface;

class ControllerAction implements ActionInterface
{
    public function handle(PageInterface $page, $parameters = [])
    {
        $controller = trim($page->getAttribute('view_action'), '\\');

        return app()->call($controller, [], 'handle');
    }
}
