<?php

namespace Soda\Cms\Http\RequestMatcher\Actions;

use Soda\Cms\Foundation\Pages\Interfaces\PageInterface;

interface ActionInterface
{
    public function handle(PageInterface $page, $parameters = []);
}
