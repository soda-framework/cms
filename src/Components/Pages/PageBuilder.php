<?php

namespace Soda\Cms\Components\Pages;

use Exception;
use Soda;
use Soda\Cms\Components\Pages\Actions\ViewAction;
use Soda\Cms\Components\Pages\Actions\ControllerAction;
use Soda\Cms\Models\Page;

class PageBuilder {
    protected $actions = [
        'view'       => ViewAction::class,
        'controller' => ControllerAction::class
    ];

    /**
     * Registers a new action
     *
     * @param $name
     * @param null $action
     */
    public function registerAction($name, $action = null){
        if(new $action instanceof ActionTypeInterface) {
            $this->actions[$name] = $action;
        }
    }

    /**
     * Registers an array of new actions
     *
     * @param $actions
     */
    public function registerActions($actions) {
        foreach($actions as $name => $action) {
            $this->register($name, $action);
        }
    }

    /**
     * Returns a list of actions that have been registered
     *
     * @return array
     */
    public function getRegisteredActions() {
        return $this->actions;
    }

    /**
     * Get a list of action types in human-readable format
     *
     * @return array
     */
    public function getActionTypes() {
        return array_map('ucfirst', array_combine($this->actions, array_keys($this->actions)));
    }

    /**
     * Loads a page by it's slug
     *
     * @param $slug
     *
     * @return \Soda\Cms\Components\Pages\PageBuilder|void
     */
    public function loadPageBySlug($slug) {
        $page = Page::with('type')->where('slug', '/' . ltrim($slug, '/'))->first();

        if ($page) {
            return $this->loadPage($page);
        }

        return $this->handleNotFound();
    }

    /**
     * Attaches a page model to our Soda instance as the 'CurrentPage'
     *
     * @param \Soda\Cms\Models\Page $page
     *
     * @return $this
     */
    public function loadPage(Page $page) {
        Soda::setCurrentPage($page);

        return $this;
    }

    /**
     * Renders the hint path and view of given page (or pageable item)
     *
     * @param $page
     * @param array $parameters
     *
     * @return string
     * @throws \Exception
     */
    public function render($page = null, $parameters = []) {
        if (!$page) {
            $page = Soda::getCurrentPage($page);
        }

        return $this->handleAction($page->action_type, $page, $parameters);
    }

    /**
     * Handles a page action
     *
     * @param $action_type
     * @param \Soda\Cms\Models\Page $page
     * @param array $parameters
     *
     * @return mixed
     * @throws \Exception
     */
    public function handleAction($action_type, Page $page, $parameters = []) {
        if(!isset($this->actions[$action_type])) {
            Throw new Exception('Action \'' . $action_type . '\' is not registered');
        }

        $handler = new $this->actions[$action_type];

        return $handler->handle($page, $parameters);
    }

    /**
     * @deprecated Handles a page edit action
     *
     * @param $page
     *
     * @return mixed
     */
    public function handleEditAction($page) {
        return $this->handleAction($page->action_type);
    }

    /**
     * Handles not found errors
     */
    protected function handleNotFound() {
        abort(404, '404 Page not found');
    }
}
