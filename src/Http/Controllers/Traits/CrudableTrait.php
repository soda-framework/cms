<?php namespace Soda\Cms\Http\Controllers\Traits;

use Auth;
use DataFilter;
use DataGrid;
use Illuminate\Http\Request;
use Soda;

Trait CrudableTrait
{
    protected $model;
    protected $saveOnCreate = false;

    public function index()
    {
        $filter = $this->buildFilter();
        $grid = $this->buildGrid($filter);
        $grid->paginate(10)->getGrid($this->getGridView());

        return view($this->getView('index'), [
            'filter' => $filter,
            'grid'   => $grid,
            'hint'   => $this->hint,
        ]);
    }

    public function view(Request $request, $id = null)
    {
        if ($id) {
            $this->model = $this->model->findOrFail($id);
        }

        if ($request->input('block_type_id')) {
            $this->model->block_type_id = $request->input('block_type_id');
        }

        return view($this->getView('view'), [
            'model' => $this->model,
            'hint'  => $this->hint,
        ]);
    }

    public function edit(Request $request, $id = null)
    {
        if ($id) {
            $this->model = $this->model->findOrFail($id);
        }

        $this->model->fill($request->input());
        $this->model->application_id = Soda::getApplication()->id;
        $this->model->save();

        return redirect()->route($this->getRouteTo('view'), [
            'id' => $this->model->id,
        ])->with('success', 'updated');
    }

    public function delete($id)
    {
        $this->model->find($id)->delete();

        return redirect()->route($this->getRouteTo())->with('success', 'updated');
    }

    protected function buildFilter()
    {
        $filter = DataFilter::source($this->model);
        $filter->add('name', 'name', 'text');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        return $filter;
    }

    protected function buildGrid($filter)
    {
        $grid = DataGrid::source($filter);  //same source types of DataSet
        $grid->add('name', 'Name', true); //field name, label, sortable
        $grid->add('description', 'Description', true); //field name, label, sortable
        $grid->add('{{ $id }}', 'Options')->cell(function ($value) {
            $edit = "<a href='".route('soda.'.$this->hint.'.edit', [$value])."' class='btn btn-warning'><span class='fa fa-pencil'></span> Edit</a> ";
            $edit .= "<a href='".route('soda.'.$this->hint.'.delete', [$value])."' class='btn btn-danger'><span class='fa fa-pencil'></span> Delete</a>";

            return $edit;
        });
        $grid->orderBy('id', 'desc'); //default orderby

        return $grid;
    }

    protected function getGridView()
    {
        return 'soda::partials.grid';
    }

    protected function getView($view = null)
    {
        return 'soda::'.$this->hint.($view ? '.'.$view : '');
    }

    protected function getRouteTo($route = null)
    {
        return 'soda.'.$this->hint.($route ? '.'.$route : '');
    }

}
