<?php
namespace Soda\Cms\Database\Permissions\Repositories;

use Illuminate\Http\Request;
use Soda\Cms\Database\Permissions\Interfaces\PermissionInterface;
use Soda\Cms\Database\Permissions\Interfaces\PermissionRepositoryInterface;
use Soda\Cms\Database\Support\Repositories\AbstractRepository;
use Soda\Cms\Database\Support\Repositories\Traits\BuildsDataGrids;
use Zofe\Rapyd\DataFilter\DataFilter;
use Zofe\Rapyd\DataGrid\DataGrid;

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
        return $this->model->roles()->getRelated()->pluck('display_name', 'id')->toArray();
    }

    public function save(Request $request, $id = null)
    {
        $model = parent::save($request, $id);

        if ($request->has('role_id')) {
            $model->roles()->sync($request->input('role_id'));
        }

        return $model;
    }

    public function buildFilter($model)
    {
        $filter = (new DataFilter)->source($model);
        $filter->add('display_name', 'Name', 'text');
        $filter->add('category', 'Category', 'select')
            ->option("", "")
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
        $grid->add('description', 'Description', true);
        $grid->add('category', 'Category', true);

        $grid->orderBy('id', 'asc');

        return $grid;
    }

    public function getFilteredGrid($perPage)
    {
        $filter = $this->buildFilter($this->model);
        $grid = $this->buildGrid($filter);
        $grid = $this->addButtonsToGrid($grid, 'soda.permissions.edit', 'soda.permissions.destroy');
        $grid->paginate($perPage)->getGrid($this->getGridView());

        return compact('filter', 'grid');
    }
}
