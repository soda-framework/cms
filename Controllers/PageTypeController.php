<?php namespace Soda\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Soda\Models\PageType;


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