<?php

namespace Soda\Cms\Http\Controllers;

use Soda\Cms\Http\Controllers\Traits\CrudableTrait;
use Soda\Cms\Models\User;

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
        $filter->add('name', 'name', 'text');;
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        $grid = \DataGrid::source($filter);  //same source types of DataSet
        $grid->add('username', 'Username', true); //field name, label, sortable
        $grid->add('email', 'Email', true); //field name, label, sortable
        $grid->add('{{ $id }}', 'Options')->cell(function ($value) {
            $edit = "<a href='" . route('soda.' . $this->hint . '.edit', [$value]) . "' class='btn btn-warning'><span class='fa fa-pencil'></span> Edit</a> ";
            $edit .= "<a href='" . route('soda.' . $this->hint . '.delete', [$value]) . "' class='btn btn-danger'><span class='fa fa-pencil'></span> Delete</a>";

            return $edit;
        });
        $grid->orderBy('id', 'desc'); //default orderby
        $grid->paginate(10)->getGrid('soda::partials.grid');
        $hint = $this->hint;

        return view('soda::' . $this->hint . '.index', compact('filter', 'grid', 'hint'));
    }

}
