<?php

namespace Soda\Cms\Http\RequestMatcher\Matchers;

use Illuminate\Http\Request;

interface MatcherInterface
{
    public function matches($slug);

    public function render(Request $request);
}
