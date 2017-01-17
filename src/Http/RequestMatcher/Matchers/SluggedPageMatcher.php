<?php

namespace Soda\Cms\Http\RequestMatcher\Matchers;

use Illuminate\Http\Request;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;
use Illuminate\Contracts\Routing\Registrar as RouterContract;
use Soda\Cms\Database\Pages\Interfaces\CachedPageRepositoryInterface;

class SluggedPageMatcher extends AbstractPageMatcher implements MatcherInterface
{
    protected $pages;
    protected $router;
    protected $matchedPage;

    public function __construct(CachedPageRepositoryInterface $pages, RouterContract $router)
    {
        $this->pages = $pages;
        $this->router = $router;
    }

    public function match($slug)
    {
        $this->matchedPage = $this->pages->findBySlug($slug);

        return $this->matchedPage;
    }

    public function render(Request $request)
    {
        app('soda')->setCurrentPage($this->matchedPage);

        $action = $this->matchedPage->getAttribute('view_action_type');
        $handleAction = 'handle'.ucfirst($action).'Action';

        if (! method_exists($this, $handleAction)) {
            throw new Exception('Action \''.ucfirst($action).'\' is not valid.');
        }

        return $this->$handleAction($request, $this->matchedPage);
    }

    public function handleControllerAction(Request $request, PageInterface $page)
    {
        $namespace = $page->package;
        $controller = trim($page->getAttribute('view_action'), '\\');

        return $this->dispatchSluggedRoute($request, $page->slug, ['uses' => $namespace ? "$namespace\\$controller" : $controller]);
    }

    public function handleViewAction(Request $request, PageInterface $page)
    {
        $view = $page->getAttribute('view_action');
        $view_params = compact('page');

        return $this->dispatchSluggedRoute($request, $page->slug, function () use ($view, $view_params) {
            return view($view, $view_params);
        });
    }
}
