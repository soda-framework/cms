<?php

namespace Soda\Cms\Http\RequestMatcher\Actions;

use Illuminate\Http\Request;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;

interface ActionInterface
{
    public function handle(Request $request, PageInterface $page, $parameters = []);
}
