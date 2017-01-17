<?php

namespace Soda\Cms\Components\Pages;

use Soda;
use Exception;
use Soda\Cms\Models\Page;

class PageBuilder
{
    protected $action_types = [
        'view'       => 'View',
        'controller' => 'Controller',
    ];

    public function getActionTypes()
    {
        return $this->action_types;
    }

    public function loadPageBySlug($slug)
    {
        $page = Page::with('type')->where('slug', '/'.ltrim($slug, '/'))->first();

        if ($page) {
            return $this->loadPage($page);
        }

        return $this->handleNotFound();
    }

    public function loadPage(Page $page)
    {
        Soda::setCurrentPage($page);

        return $this;
    }

    /**
     * Renders the hint path and view of given page (or pageable item).
     *
     * @param $page
     * @param array $params
     *
     * @return string
     * @throws \Exception
     */
    public function render($page = null, $params = [])
    {
        if (! $page) {
            $page = Soda::getCurrentPage($page);
        }

        switch ($page->action_type) {
            case 'controller':
                return App::call($page->action);
            case 'view':
                $view = $page->package.'::'.$page->action;
                $view_params = array_merge(compact('page'), $params);

                return view($view, $view_params);
        }

        throw new Exception('Page action type \''.$page->action_type.'\' is invalid.');
    }

    public function handleEditAction($page)
    {
        if ($page->edit_action_type == 'view') {
            if (! $page->edit_action) {
                return view('soda::page.view', ['page' => $page]);
            } else {
            }
        }
    }

    protected function handleNotFound()
    {
        abort(404, '404 Page not found');
    }
}
