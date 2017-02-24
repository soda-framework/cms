<?php

namespace Soda\Cms\Database\Users\Repositories;

use Illuminate\Http\Request;
use Zofe\Rapyd\DataGrid\DataGrid;
use Zofe\Rapyd\DataFilter\DataFilter;
use Soda\Cms\Database\Users\Interfaces\UserInterface;
use Soda\Cms\Database\Support\Repositories\AbstractRepository;
use Soda\Cms\Database\Users\Interfaces\UserRepositoryInterface;
use Soda\Cms\Database\Support\Repositories\Traits\BuildsDataGrids;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    use BuildsDataGrids;

    protected $model;

    public function __construct(UserInterface $model)
    {
        $this->model = $model;
    }

    public function getRoles($filterLevel = true)
    {
        $query = $this->model->roles()->getRelated();

        if ($filterLevel) {
            $query->where('level', '<', \Auth::user()->getLevel());
        }

        return $query->pluck('display_name', 'id')->toArray();
    }

    public function save(Request $request, $id = null)
    {
        $model = $id ? $this->model->findOrFail($id) : $this->newInstance();
        $model->fill($request->except('password', 'password_confirmation'));

        if ($request->has('password') && $request->input('password')) {
            $model->password = \Hash::make($request->input('password'));
        }

        $model->save();

        if ($request->has('role_id')) {
            $model->roles()->sync($request->input('role_id'));
        }

        return $model;
    }

    /**
     * @param UserInterface $model
     */
    public function buildFilter($model)
    {
        $filter = (new DataFilter)->source($model);
        $filter->add('username', 'Username', 'text');
        $filter->add('email', 'Email', 'text');
        $filter->add('roles.id', 'Role', 'select')
            ->option('', '')
            ->options($this->getRoles(false));
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
                if ($editRoute == true && $model->getLevel() <= \Auth::user()->getLevel()) {
                    $buttons .= "<a href='".route($editRoute, $value)."' class='btn btn-warning'><i class='fa fa-pencil'></i> <span>Edit</span></a> ";
                }
                if ($deleteRoute == true && $model->getLevel() < \Auth::user()->getLevel()) {
                    $buttons .= "<a href='".route($deleteRoute, $value)."' class='btn btn-danger' data-delete-button><i class='fa fa-remove'></i> <span>Delete</span></a> ";
                }

                return $buttons;
            });
        }

        return $grid;
    }
}
