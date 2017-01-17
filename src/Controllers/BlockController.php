<?php

namespace Soda\Controllers;

use Soda\Models\Block;
use App\Http\Controllers\Controller;

class BlockController extends Controller
{
    use    Traits\CrudableTrait;

    public $type = 'block';
    public $index_view = 'soda::crudable.index';
    public $view_view = 'soda::crudable.view';

    public function __construct(Block $block)
    {
        //$this->middleware('auth');
        $this->model = $block;
    }
}
