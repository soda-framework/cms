<?php

namespace Soda\Cms\Foundation\Pages\Actions;

use Soda\Cms\Models\Page;
use Illuminate\Http\Request;

interface ActionInterface
{
    public function handle(Request $request, Page $page, $parameters = []);
}
