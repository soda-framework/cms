<?php

namespace Soda\Cms\Database\Roles\Repositories;

use Illuminate\Http\Request;
use Zofe\Rapyd\DataGrid\DataGrid;
use Zofe\Rapyd\DataFilter\DataFilter;
use Soda\Cms\Database\Roles\Interfaces\RoleInterface;
use Soda\Cms\Database\Support\Repositories\AbstractRepository;
use Soda\Cms\Database\Roles\Interfaces\RoleRepositoryInterface;
use Soda\Cms\Database\Support\Repositories\Traits\BuildsDataGrids;

class RoleRepository extends AbstractRepository implements RoleRepositoryInterface
{
    use BuildsDataGrids;

    protected $model;

    public function __construct(RoleInterface $model)
    {
        $this->model = $model;
    }

    public function getPermissions()
    {
        return $this->model->permissions()->getRelated()->get()->groupBy('category')->map(function ($item, $key) {
            return $item->pluck('display_name', 'id');
        })->toArray();
    }

    public function save(Request $request, $id = null)
    {
        $model = parent::save($request, $id);

        if ($request->has('permission_id')) {
            $model->permissions()->sync($request->input('permission_id'));
        }

        return $model;
    }

    /**
     * @param RoleInterface $model
     */
    public function buildFilter($model)
    {
        $filter = (new DataFilter)->source($model);
        $filter->add('display_name', 'Name', 'text');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        return $filter;
    }

    public function buildGrid(DataFilter $filter)
    {
        $grid = (new DataGrid)->source($filter);
        $grid->add('display_name', 'Display Name', true);
        $grid->add('description', 'Description', true);

        $grid->orderBy('id', 'asc');

        return $grid;
    }

    public function getFilteredGrid($perPage)
    {
        $filter = $this->buildFilter($this->model);
        $grid = $this->buildGrid($filter);
        $grid = $this->addButtonsToGrid($grid, 'soda.roles.edit', 'soda.roles.destroy');
        $grid->paginate($perPage)->getGrid($this->getGridView());

        return compact('filter', 'grid');
    }

    /**
     * @param string $editRoute
     * @param string $deleteRoute
     *
     * @return DataGrid
     */
    public function addButtonsToGrid(DataGrid $grid, $editRoute = null, $deleteRoute = null)
    {
        $width = 0;

        if ($editRoute == true) {
            $width += 80;
        }
        if ($deleteRoute == true) {
            $width += 100;
        }

        if ($width > 0) {
            $grid->add('{{ $id }}', 'Options')->style('width:'.$width.'px')->cell(function ($value, $model) use ($editRoute, $deleteRoute) {
                $buttons = '';
                if ($editRoute == true && $model->level < \Auth::user()->getLevel()) {
                    $buttons .= "<a href='".route($editRoute, $value)."' class='btn btn-warning'><i class='fa fa-pencil'></i> <span>Edit</span></a> ";
                }
                if ($deleteRoute == true && $model->level < \Auth::user()->getLevel()) {
                    $buttons .= "<a href='".route($deleteRoute, $value)."' class='btn btn-danger' data-delete-button><i class='fa fa-remove'></i> <span>Delete</span></a> ";
                }

                return $buttons;
            });
        }

        return $grid;
    }
}
