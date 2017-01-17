<?php

namespace Soda\Cms\Http\Controllers;

use Soda\Cms\Models\PageType;
use Soda\Cms\Http\Controllers\Traits\CrudableTrait;

class PageTypeController extends BaseController
{
    use CrudableTrait;
    public $hint = 'page-type';

    public function __construct(PageType $type)
    {
        $this->model = $type;
    }
}
