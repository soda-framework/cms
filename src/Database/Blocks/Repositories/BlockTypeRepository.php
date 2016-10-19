<?php
namespace Soda\Cms\Database\Blocks\Repositories;

use Soda;
use Soda\Cms\Database\Blocks\Interfaces\BlockInterface;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeRepositoryInterface;
use Soda\Cms\Database\Support\Repositories\AbstractRepository;
use Soda\Cms\Database\Support\Repositories\Traits\BuildsDataGrids;

class BlockTypeRepository extends AbstractRepository implements BlockTypeRepositoryInterface
{
    use BuildsDataGrids;
    protected $model;

    public function __construct(BlockInterface $model)
    {
        $this->model = $model;
    }

    public function getFilteredGrid($perPage)
    {
        $filter = $this->buildFilter($this->model);
        $grid = $this->buildGrid($filter);
        $grid = $this->addButtonsToGrid($grid, 'soda.block-types.edit', 'soda.block-types.delete');
        $grid->paginate($perPage)->getGrid($this->getGridView());

        return compact('filter', 'grid');
    }
}
