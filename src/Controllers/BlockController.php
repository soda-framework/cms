<?php

namespace Soda\Controllers;

use Soda\Models\Block;
use App\Http\Controllers\Controller;

class BlockController extends Controller
{
    use    Traits\CrudableTrait;

    public $hint = 'block';

    public function __construct(Block $block)
    {
        //$this->middleware('auth');
        $this->model = $block;
    }
}
