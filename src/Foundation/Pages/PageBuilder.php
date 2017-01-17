<?php

namespace Soda\Cms\Foundation\Pages;

use Soda;
use Exception;
use Soda\Cms\Models\Page;
use Soda\Cms\Models\Block;
use Illuminate\Http\Request;
use Soda\Cms\Models\PageType;
use Soda\Cms\Models\BlockType;
use Soda\Cms\Foundation\Pages\Actions\ViewAction;
use Soda\Cms\Foundation\Pages\Actions\ActionInterface;
use Soda\Cms\Foundation\Pages\Actions\ControllerAction;

class PageBuilder
{
    protected $actions = [
        'view'       => ViewAction::class,
        'controller' => ControllerAction::class,
    ];

    protected $draftables = [
        Block::class,
        BlockType::class,
        Page::class,
        PageType::class,
    ];

    /**
     * Registers a new action.
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
     * Registers an array of new actions.
     *
     * @param $actions
     */
    public function registerActions($actions)
    {
        foreach ($actions as $name => $action) {
            $this->registerAction($name, $action);
        }
    }

    /**
     * Registers a new draftable.
     *
     * @param null $draftable
     */
    public function registerDraftable($draftable)
    {
        // Check if class uses Draftable trait
        if (method_exists($draftable, 'bootDraftableTrait')) {
            $this->draftables[] = $draftable;
        }
    }

    /**
     * Registers an array of draftables.
     *
     * @param $draftables
     */
    public function registerDraftables($draftables)
    {
        foreach ($draftables as $draftable) {
            $this->registerDraftable($draftable);
        }
    }

    /**
     * Returns a list of actions that have been registered.
     *
     * @return array
     */
    public function getRegisteredActions()
    {
        return $this->actions;
    }

    /**
     * Returns a list of draftables that have been registered.
     *
     * @return array
     */
    public function getDraftables()
    {
        return $this->draftables;
    }

    /**
     * Get a list of action types in human-readable format.
     *
     * @return array
     */
    public function getActionTypes()
    {
        return array_map('ucfirst', array_combine(array_keys($this->actions), array_keys($this->actions)));
    }

    /**
     * Loads a page by it's slug.
     *
     * @param $slug
     *
     * @return \Soda\Cms\Foundation\Pages\PageBuilder|void
     */
    public function loadPageBySlug($slug)
    {
        if ($ttl = config('soda.cache.pages') === true) {
            $page = \Cache::remember('soda.'.\Soda::getApplication()->id.'.page.slug-'.$slug, is_int($ttl) ? $ttl : config('soda.cache.default-ttl'), function () use ($slug) {
                $page = Page::where('slug', '/'.ltrim($slug, '/'))->first();

                if (config('soda.cache.page-type') === true) {
                    $page->load('type');
                }

                if (config('soda.cache.page-blocks') === true) {
                    $page->load('blocks');
                }

                return $page;
            });
        } else {
            $page = Page::where('slug', '/'.ltrim($slug, '/'))->first();
        }

        if ($page) {
            if (config('soda.cache.page-type') !== true && ! $page->relationLoaded('type')) {
                $page->load('type');
            }

            if (config('soda.cache.page-blocks') !== true && ! $page->relationLoaded('blocks')) {
                $page->load('blocks');
            }

            return $this->loadPage($page);
        }

        return $this->handleNotFound();
    }

    /**
     * Attaches a page model to our Soda instance as the 'CurrentPage'.
     *
     * @param \Soda\Cms\Models\Page $page
     *
     * @return $this
     */
    public function loadPage(Page $page)
    {
        Soda::setCurrentPage($page);

        return $this;
    }

    /**
     * Renders the hint path and view of given page (or pageable item).
     *
     * @param Request $request
     * @param         $page
     * @param array   $parameters
     *
     * @return string
     * @throws Exception
     */
    public function render(Request $request, $page = null, $parameters = [])
    {
        if (! $page) {
            $page = Soda::getCurrentPage($page);
        }

        return $this->handleAction($request, $page->action_type, $page, $parameters);
    }

    /**
     * Handles a page action.
     *
     * @param Request               $request
     * @param                       $action_type
     * @param \Soda\Cms\Models\Page $page
     * @param array                 $parameters
     *
     * @return mixed
     * @throws Exception
     */
    public function handleAction(Request $request, $action_type, Page $page, $parameters = [])
    {
        if (! isset($this->actions[$action_type])) {
            throw new Exception('Action \''.$action_type.'\' is not registered');
        }

        $handler = new $this->actions[$action_type];

        return $handler->handle($request, $page, $parameters);
    }

    /**
     * @deprecated Handles a page edit action
     *
     * @param $page
     *
     * @return mixed
     */
    public function handleEditAction($page)
    {
        return $this->handleAction($page->action_type);
    }

    /**
     * Handles not found errors.
     */
    protected function handleNotFound()
    {
        abort(404, '404 Page not found');
    }
}
