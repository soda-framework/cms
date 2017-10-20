<?php

namespace Soda\Cms\Database\Repositories;

use Illuminate\Http\Request;
use Zofe\Rapyd\DataGrid\DataGrid;
use Zofe\Rapyd\DataFilter\DataFilter;
use Soda\Cms\Database\Repositories\Traits\BuildsDataGrids;
use Soda\Cms\Database\Models\Contracts\PermissionInterface;
use Soda\Cms\Database\Repositories\Contracts\PermissionRepositoryInterface;

class PermissionRepository extends AbstractRepository implements PermissionRepositoryInterface
{
    use BuildsDataGrids;

    protected $model;

    public function __construct(PermissionInterface $model)
    {
        $this->model = $model;
    }

    public function getRoles()
    {
        return $this->model->roles()->getRelated()->where('level', '<', \Auth::user()->getLevel())->pluck('display_name', 'id')->toArray();
    }

    public function save(Request $request, $id = null)
    {
        $model = parent::save($request, $id);

        if ($request->has('role_id')) {
            $model->roles()->sync($request->input('role_id'));
        }

        return $model;
    }

    public function getFilteredGrid($perPage)
    {
        $filter = $this->buildFilter($this->model);
        $grid = $this->buildGrid($filter);
        $grid = $this->addButtonsToGrid($grid, 'soda.permissions.edit', 'soda.permissions.destroy');
        $grid->paginate($perPage)->getGrid($this->getGridView());

        return compact('filter', 'grid');
    }

    /**
     * @param PermissionInterface $model
     */
    public function buildFilter($model)
    {
        $filter = (new DataFilter)->source($model);
        $filter->add('display_name', 'Name', 'text');
        $filter->add('category', 'Category', 'select')
            ->option('', '')
            ->options($this->model->pluck('category', 'category'));
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        return $filter;
    }

    public function buildGrid(DataFilter $filter)
    {
        $grid = (new DataGrid)->source($filter);
        $grid->add('display_name', 'Display Name', true);
        $grid->add('description', 'Description')->attributes(['class' => 'hidden-sm hidden-xs']);
        $grid->add('category', 'Category', true)->attributes(['class' => 'hidden-xs']);

        $grid->row(function ($row) {
            $row->cell('description')->attributes(['class' => 'hidden-sm hidden-xs']);
            $row->cell('category')->attributes(['class' => 'hidden-xs']);
        });

        $grid->orderBy('id', 'asc');
        $grid->attr('class', 'table table-striped middle');

        return $grid;
    }
}
