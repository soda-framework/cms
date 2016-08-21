<?php namespace Soda\Cms\Controllers;

use App\Http\Controllers\Controller;
use Redirect;
use Soda\Cms\Models\Block;
use Soda\Cms\Controllers\Traits\CrudableTrait;

class BlockController extends Controller {

    use CrudableTrait;

    protected $hint = 'block';

    public function __construct(Block $block) {
        $this->model = $block;
    }


}
