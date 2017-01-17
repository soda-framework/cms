<?php

namespace Soda\Cms\Controllers;

use Soda\Cms\Models\BlockType;
use App\Http\Controllers\Controller;
use Soda\Cms\Controllers\Traits\CrudableTrait;

class BlockTypeController extends Controller
{
    use CrudableTrait;
    protected $hint = 'block_type';

    public function __construct(BlockType $type)
    {
        $this->model = $type;
    }
}
