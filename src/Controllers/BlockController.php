<?php

namespace Soda\Cms\Controllers;

use Soda\Cms\Models\Block;
use Soda\Cms\Models\BlockType;
use App\Http\Controllers\Controller;
use Soda\Cms\Controllers\Traits\CrudableTrait;

class BlockController extends Controller
{
    use CrudableTrait;

    protected $hint = 'block';

    public function __construct(Block $block)
    {
        $this->model = $block;
    }

    public function index()
    {
        $filter = $this->buildFilter();
        $grid = $this->buildGrid($filter);
        $grid->paginate(10)->getGrid($this->getGridView());

        $block_types = BlockType::get();

        return view($this->getView('index'), [
            'filter'      => $filter,
            'grid'        => $grid,
            'hint'        => $this->hint,
            'block_types' => $block_types,
        ]);
    }
}
