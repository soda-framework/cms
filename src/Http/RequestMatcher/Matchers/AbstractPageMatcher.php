<?php

namespace Soda\Cms\Http\RequestMatcher\Matchers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\Registrar as RouterContract;

abstract class AbstractPageMatcher implements MatcherInterface
{
    protected $router;

    public function __construct(RouterContract $router)
    {
        $this->router = $router;
    }

    abstract public function matches($slug);

    abstract public function render(Request $request);
}
