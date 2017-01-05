<?php

namespace Soda\Cms\Foundation\Pages\Actions;

use Illuminate\Http\Request;
use Soda\Cms\Models\Page;

interface ActionInterface
{
    public function handle(Request $request, Page $page, $parameters = []);
}
