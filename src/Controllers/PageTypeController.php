<?php

namespace Soda\Controllers;

use Soda\Models\PageType;
use App\Http\Controllers\Controller;

class PageTypeController extends Controller
{
    use Traits\CrudableTrait;

    public $index_view = 'soda::standard.index';
    public $view_view = 'soda::standard.view';

    public function __construct(PageType $type)
    {
        //$this->middleware('auth');
        $this->model = $type;
    }
}
