<?php

namespace Soda\Cms\Components\Pages;

use Soda\Cms\Models\Page;

interface ActionTypeInterface
{
    public function handle(Page $page, $parameters = []);
}
