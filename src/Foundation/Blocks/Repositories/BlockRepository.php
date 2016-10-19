<?php
namespace Soda\Cms\Foundation\Blocks\Repositories;

use Illuminate\Http\Request;
use Soda;
use Soda\Cms\Foundation\Blocks\Interfaces\BlockInterface;
use Soda\Cms\Foundation\Blocks\Interfaces\BlockRepositoryInterface;
use Soda\Cms\Foundation\Support\Traits\HasDataGridTrait;

class BlockRepository implements BlockRepositoryInterface
{
    use HasDataGridTrait;
    protected $model;

    public function __construct(BlockInterface $model)
    {
        $this->model = $model;
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function getBlockTypes()
    {
        return $this->model->type()->getRelated()->get();
    }

    public function getFilteredBlockGrid($perPage)
    {
        $filter = $this->buildFilter($this->model);
        $grid = $this->buildGrid($filter);
        $grid = $this->addButtonsToGrid($grid, 'soda.blocks.edit', 'soda.blocks.delete');
        $grid->paginate($perPage)->getGrid($this->getGridView());

        return compact('filter', 'grid');
    }

    public function createStub($blockTypeId = null)
    {
        $block = $this->model->newInstance([
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

    public function save(Request $request, $id = null)
    {
        $block = $id ? $this->model->findOrFail($id) : $this->model->newInstance();
        $block->fill($request->all())->save();

        return $block;
    }

    public function destroy($id)
    {
        $block = $this->model->find($id);
        $block->delete();

        return $block;
    }
}
