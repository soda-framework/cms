<?php

namespace Soda\Cms\Components\Pages\Actions;

use Soda\Cms\Components\Pages\ActionTypeInterface;
use Soda\Cms\Models\Page;

class ViewAction implements ActionTypeInterface
{
    public function handle(Page $page, $parameters = [])
    {
        $view = ($page->package && !str_contains($page->action, '::') ? $page->package.'::' : '').$page->action;
        $view_params = array_merge(compact('page'), $parameters);

        return view($view, $view_params);
    }
}
