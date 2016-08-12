<?php namespace Soda\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Soda\Models\BlockType;


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