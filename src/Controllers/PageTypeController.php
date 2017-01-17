<?php

namespace Soda\Cms\Controllers;

use Soda\Cms\Models\PageType;
use App\Http\Controllers\Controller;
use Soda\Cms\Controllers\Traits\CrudableTrait;

class PageTypeController extends Controller
{
    use CrudableTrait;
    public $hint = 'page_type';

    public function __construct(PageType $type)
    {
        $this->model = $type;
    }
}
