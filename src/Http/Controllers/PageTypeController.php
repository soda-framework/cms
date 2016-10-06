<?php

namespace Soda\Cms\Http\Controllers;

use Soda\Cms\Http\Controllers\Traits\CrudableTrait;
use Soda\Cms\Models\PageType;

class PageTypeController extends BaseController
{
    use CrudableTrait;
    public $hint = 'page_type';

    public function __construct(PageType $type)
    {
        $this->model = $type;
    }
}
