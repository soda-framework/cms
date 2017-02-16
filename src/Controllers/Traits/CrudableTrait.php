<?php

namespace Soda\Controllers\Traits;

use Auth;
use Soda;
use Illuminate\Http\Request;

trait CrudableTrait
{
    public $model;

    public function index()
    {
        $filter = \DataFilter::source($this->model);
        $filter->add('name', 'name', 'text');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        $grid = \DataGrid::source($filter);  //same source types of DataSet
        $grid->add('name', 'Name', true); //field name, label, sortable
        $grid->add('description', 'Description', true); //field name, label, sortable
        $grid->add('{{ $id }}', 'Options')->cell(function ($value) {
            $edit = "<a href='".route('soda.'.$this->hint.'.edit', [$value])."' class='btn btn-warning'><span class='fa fa-pencil'></span> Edit</a> ";
            $edit .= "<a href='".route('soda.'.$this->hint.'.delete', [$value])."' class='btn btn-danger'><span class='fa fa-pencil'></span> Delete</a>";

            return $edit;
        });
        $grid->orderBy('id', 'desc'); //default orderby
        $grid->paginate(10)->getGrid('soda::partials.grid');
        $hint = $this->hint;

        return view('soda::'.$this->hint.'.index', compact('filter', 'grid', 'hint'));
    }

    public function view($id = null)
    {
        if (! $id) {
            $model = $this->model;
        } else {
            $model = $this->model->find($id);
        }
        $hint = $this->hint;

        return view('soda::'.$this->hint.'.view', compact('model', 'hint'));
    }

    public function edit(Request $request, $id = null)
    {
        if ($id) {
            $this->model = $this->model->findOrFail($id);
        }

        $this->model->fill($request->input());
        $this->model->application_id = Soda::getApplication()->id;
        $this->model->application_user_id = Auth::user()->id;
        $this->model->save();

        return redirect()->route('soda.'.$this->hint.'.view', ['id' => $this->model->id])->with('success',
            'updated');
    }

    public function delete($id)
    {
        $this->model->where('id', $id)->delete();

        return redirect()->route('soda.'.$this->hint)->with('success', 'updated');
    }
}
