<?php
namespace Soda\Cms\Database\Blocks\Repositories;

use Soda;
use Soda\Cms\Database\Blocks\Interfaces\BlockInterface;
use Soda\Cms\Database\Blocks\Interfaces\BlockRepositoryInterface;
use Soda\Cms\Database\Support\Repositories\AbstractRepository;
use Soda\Cms\Database\Support\Repositories\Traits\BuildsDataGrids;

class BlockRepository extends AbstractRepository implements BlockRepositoryInterface
{
    use BuildsDataGrids;

    protected $model;

    public function __construct(BlockInterface $model)
    {
        $this->model = $model;
    }

    public function getTypes()
    {
        return $this->model->type()->getRelated()->get();
    }

    public function getFilteredGrid($perPage)
    {
        $filter = $this->buildFilter($this->model);
        $grid = $this->buildGrid($filter);
        $grid = $this->addButtonsToGrid($grid, 'soda.blocks.edit', 'soda.blocks.delete');
        $grid->paginate($perPage)->getGrid($this->getGridView());

        return compact('filter', 'grid');
    }

    public function createStub($blockTypeId = null)
    {
        $block = $this->newInstance([
            'block_type_id' => $blockTypeId,
        ]);

        if ($blockTypeId) {
            $block->load('type');

            if ($block->relationLoaded('type')) {
                $block->name = $block->type->name;
                $block->description = $block->type->description;
                $block->identifier = $block->type->identifier;
                $block->edit_action = $block->type->edit_action;
                $block->edit_action_type = $block->type->edit_action_type;
                $block->list_action = $block->type->list_action;
                $block->list_action_type = $block->type->list_action_type;
            }
        }

        return $block;
    }
}