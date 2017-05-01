<?php

namespace Soda\Cms\Http\RequestMatcher\Matchers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Models\Contracts\ContentInterface;
use Illuminate\Contracts\Routing\Registrar as RouterContract;
use Soda\Cms\Database\Repositories\Contracts\ContentRepositoryInterface;

class SluggedContentMatcher extends AbstractContentMatcher implements MatcherInterface
{
    protected $content;
    protected $router;
    protected $match;

    public function __construct(ContentRepositoryInterface $content, RouterContract $router)
    {
        $this->content = $content;
        $this->router = $router;
    }

    public function matches($slug)
    {
        $this->match = $this->content->findBySlug($slug);

        return $this->match;
    }

    public function render(Request $request)
    {
        app('soda')->setCurrentPage($this->match);

        $action = $this->match->getAttribute('view_action_type');
        $handleAction = 'handle'.ucfirst($action).'Action';

        if (! method_exists($this, $handleAction)) {
            throw new Exception('Action \''.ucfirst($action).'\' is not valid.');
        }

        return $this->$handleAction($this->match);
    }

    public function handleControllerAction(ContentInterface $content)
    {
        $namespace = $content->package;
        $controller = trim($content->getAttribute('view_action'), '\\');

        return ['uses' => $namespace ? "$namespace\\$controller" : $controller];
    }

    public function handleViewAction(ContentInterface $content)
    {
        $view = $content->getAttribute('view_action');
        $view_params = ['page' => $content];

        return function () use ($view, $view_params) {
            return view($view, $view_params);
        };
    }
}
