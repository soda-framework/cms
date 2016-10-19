<?php

namespace Soda\Cms\Http\RequestMatcher\Actions;

use Soda\Cms\Foundation\Pages\Interfaces\PageInterface;

class ViewAction implements ActionInterface
{
    public function handle(PageInterface $page, $parameters = [])
    {
        $view = ($page->package && !str_contains($page->action, '::') ? $page->package.'::' : '').$page->action;
        $view_params = array_merge(compact('page'), $parameters);

        return view($view, $view_params);
    }
}
