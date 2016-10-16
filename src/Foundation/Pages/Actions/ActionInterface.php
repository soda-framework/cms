<?php

namespace Soda\Cms\Foundation\Pages\Actions;

use Soda\Cms\Models\Page;

interface ActionInterface
{
    public function handle(Page $page, $parameters = []);
}
