<?php

namespace  Soda\Cms\Http\Matcher;

use Exception;
use Soda;
use Soda\Cms\Foundation\Application\Interfaces\CachedApplicationRepositoryInterface;
use Soda\Cms\Foundation\Pages\Interfaces\CachedPageRepositoryInterface;
use Soda\Cms\Foundation\Pages\Interfaces\PageInterface;
use Soda\Cms\Http\Matcher\Actions\ActionInterface;
use Soda\Cms\Http\Matcher\Actions\ControllerAction;
use Soda\Cms\Http\Matcher\Actions\ViewAction;

class RequestMatcher
{
    protected $application;
    protected $pages;

    protected $actions = [
        'view'       => ViewAction::class,
        'controller' => ControllerAction::class,
    ];

    public function __construct(CachedPageRepositoryInterface $pages, CachedApplicationRepositoryInterface $application)
    {
        $this->pages = $pages;
        $this->application = $application;
    }

    /**
     * Registers a new action
     *
     * @param      $name
     * @param null $action
     */
    public function registerAction($name, $action = null)
    {
        if (new $action instanceof ActionInterface) {
            $this->actions[$name] = $action;
        }
    }

    /**
     * Registers an array of new actions
     *
     * @param $actions
     */
    public function registerActions($actions)
    {
        foreach ($actions as $name => $action) {
            $this->register($name, $action);
        }
    }

    /**
     * Returns a list of actions that have been registered
     *
     * @return array
     */
    public function getRegisteredActions()
    {
        return $this->actions;
    }

    /**
     * Get a list of action types in human-readable format
     *
     * @return array
     */
    public function getActionTypes()
    {
        return array_map('ucfirst', array_combine(array_keys($this->actions), array_keys($this->actions)));
    }

    public function matchApplication($url) {
        $application = $this->application->findByUrl($url);

        if ($application) {
            return $application;
        }

        return $this->handleApplicationNotFound();
    }

    /**
     * Loads a page by it's slug
     *
     * @param $slug
     *
     * @return \Soda\Cms\Foundation\Pages\PageBuilder|void
     */
    public function match($slug)
    {
        $page = $this->pages->findBySlug($slug);

        if ($page) {
            Soda::setCurrentPage($page);

            return $this;
        }

        return $this->handlePageNotFound();
    }

    /**
     * Renders the hint path and view of given page (or pageable item)
     *
     * @param       $page
     * @param array $parameters
     *
     * @return string
     * @throws \Exception
     */
    public function render($page = null, $parameters = [])
    {
        if (!$page) {
            $page = Soda::getCurrentPage($page);
        }

        return $this->handleAction($page->action_type, $page, $parameters);
    }

    /**
     * Handles a page action
     *
     * @param               $action_type
     * @param PageInterface $page
     * @param array         $parameters
     *
     * @return mixed
     * @throws Exception
     */
    public function handleAction($action_type, PageInterface $page, $parameters = [])
    {
        if (!isset($this->actions[$action_type])) {
            Throw new Exception('Action \''.$action_type.'\' is not registered');
        }

        $handler = new $this->actions[$action_type];

        return $handler->handle($page, $parameters);
    }

    /**
     * Handles not found errors
     */
    protected function handleApplicationNotFound()
    {
        abort(404, 'No Application Found at URL');
    }

    /**
     * Handles not found errors
     */
    protected function handlePageNotFound()
    {
        abort(404, '404 Page not found');
    }
}
