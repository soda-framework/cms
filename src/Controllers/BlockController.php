<?php namespace Soda\Controllers;

use App\Http\Controllers\Controller;
use Soda\Models\Block;
use Redirect;

class BlockController extends Controller
{

    use    Traits\CrudableTrait;

    public $hint = 'block';

    public function __construct(Block $block) {
        //$this->middleware('auth');
        $this->model = $block;
    }



}