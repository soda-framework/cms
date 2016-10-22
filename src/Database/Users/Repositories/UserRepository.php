<?php
namespace Soda\Cms\Database\Users\Repositories;

use Illuminate\Http\Request;
use Soda\Cms\Database\Users\Interfaces\UserInterface;
use Soda\Cms\Database\Users\Interfaces\UserRepositoryInterface;
use Soda\Cms\Database\Support\Repositories\AbstractRepository;
use Soda\Cms\Database\Support\Repositories\Traits\BuildsDataGrids;
use Zofe\Rapyd\DataFilter\DataFilter;
use Zofe\Rapyd\DataGrid\DataGrid;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    use BuildsDataGrids;

    protected $model;

    public function __construct(UserInterface $model)
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

        if($request->has('role_id')) {
            $model->roles()->sync($request->input('role_id'));
        }

        return $model;
    }

    public function buildFilter($model)
    {
        $filter = (new DataFilter)->source($model);
        $filter->add('username', 'Username', 'text');
        $filter->add('email', 'Email', 'text');
        $filter->add('roles.id', 'Role', 'select')
            ->option("","")
            ->options($this->getRoles());
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        return $filter;
    }

    public function buildGrid(DataFilter $filter)
    {
        $grid = (new DataGrid)->source($filter);
        $grid->add('username', 'Username', true);
        $grid->add('email', 'Email', true);
        $grid->add('roles', 'Role(s)')->cell(function ($value) {
            $roleNames = $value->sortBy('display_name')->pluck('display_name')->toArray();

            return implode(', ', $roleNames);
        });

        $grid->orderBy('id', 'asc');

        return $grid;
    }

    public function getFilteredGrid($perPage)
    {
        $filter = $this->buildFilter($this->model);
        $grid = $this->buildGrid($filter);
        $grid = $this->addButtonsToGrid($grid, 'soda.users.edit', 'soda.users.destroy');
        $grid->paginate($perPage)->getGrid($this->getGridView());

        return compact('filter', 'grid');
    }
}
