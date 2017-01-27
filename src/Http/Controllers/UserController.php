<?php

namespace Soda\Cms\Http\Controllers;

use Soda\Cms\Models\User;
use Illuminate\Http\Request;
use Soda\Cms\Http\Controllers\Traits\CrudableTrait;

class UserController extends BaseController
{
    use CrudableTrait;

    public $hint = 'user';

    public function __construct(User $user)
    {
        //$this->middleware('auth');
        $this->model = $user;
    }

    public function index()
    {
        $filter = \DataFilter::source($this->model);
        $filter->add('name', 'name', 'text');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        $grid = \DataGrid::source($filter);  //same source types of DataSet
        $grid->add('username', 'Username', true); //field name, label, sortable
        $grid->add('email', 'Email', true); //field name, label, sortable
        $grid->add('{{ $id }}', 'Options')->style('width:180px')->cell(function ($value) {
            $edit = "<a href='".route('soda.'.$this->hint.'.edit', [$value])."' class='btn btn-warning'><i class='fa fa-pencil'></i> <span>Edit</span></a> ";
            $edit .= "<a href='".route('soda.'.$this->hint.'.delete', [$value])."' class='btn btn-danger'><i class='fa fa-remove'></i> <span>Delete</span></a>";

            return $edit;
        });
        $grid->orderBy('id', 'desc'); //default orderby
        $grid->paginate(10)->getGrid(soda_cms_view_path('partials.grid'));
        $hint = $this->hint;

        return soda_cms_view($this->hint.'.index', compact('filter', 'grid', 'hint'));
    }

    public function edit(Request $request, $id = null)
    {
        if ($id) {
            $this->model = $this->model->findOrFail($id);
        }

        $this->validate($request, [
            'password' => 'sometimes|confirmed',
        ]);

        $this->model->fill($request->only('username, email'));
        $this->model->application_id = \Soda::getApplication()->id;
        $this->model->roles()->sync($request->input('roles'));

        if($request->has('password') && trim($request->input('password')))
        {
            $this->model->password = \Hash::make($request->input('password'));
        }

        $this->model->save();

        return redirect()->route($this->getRouteTo('view'), [
            'id' => $this->model->id,
        ])->with('success', 'updated');
    }
}
