<?php

namespace Soda\Controllers\Traits;

use Illuminate\Http\Request;

trait CrudableTrait
{
    public $model;

    public function index()
    {
        $this->model = $this->model->paginate(15);

        $fields = $this->model->first()->getAttributes();

        return view($this->index_view, ['models' => $this->model, 'fields' => $fields, 'type' => $this->type]);
    }

    public function edit(Request $request, $id = null)
    {
        if ($id) {
            $this->model = $this->model->findOrFail($id);
        }

        $this->model->fill($request->input());
        $this->model->save();

        return redirect()->route('soda.'.$this->model->title.'.view', ['id' => $this->model->id])->with('success',
            'field updated');
    }

    public function view($id = null)
    {
        if ($id) {
            $model = $this->model->findOrFail($id);
        } else {
            $model = $this->model;
        }

        $fields = $model->view_fields;

        return view($this->view_view, ['model' => $model, 'fields' => $fields]);
    }

    public function delete()
    {
        //TODO:
    }
}
