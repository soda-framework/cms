<?php

namespace Soda\Controllers;

use Soda\Models\BlockType;
use App\Http\Controllers\Controller;

class BlockTypeController extends Controller
{
    use Traits\CrudableTrait;
    public $hint = 'block_type';

    public function __construct(BlockType $type)
    {
        //$this->middleware('auth');
        $this->model = $type;
    }
}
