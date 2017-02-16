<?php

namespace Soda\Controllers;

use Soda\Models\PageType;
use App\Http\Controllers\Controller;

class PageTypeController extends Controller
{
    use Traits\CrudableTrait;
    public $hint = 'page_type';

    public function __construct(PageType $type)
    {
        //$this->middleware('auth');
        $this->model = $type;
    }
}
