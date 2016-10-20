<?php

namespace Soda\Cms\Http\RequestMatcher\Actions;

use Soda\Cms\Database\Pages\Interfaces\PageInterface;

class ViewAction implements ActionInterface
{
    public function handle(PageInterface $page, $parameters = [])
    {
        $view = $page->getAttribute('view_action');
        $view_params = array_merge(compact('page'), $parameters);

        return view($view, $view_params);
    }
}
