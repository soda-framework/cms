<?php

namespace Soda\Cms\Http\RequestMatcher\Actions;

use Soda\Cms\Database\Pages\Interfaces\PageInterface;

interface ActionInterface
{
    public function handle(PageInterface $page, $parameters = []);
}
