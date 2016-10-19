<?php

namespace Soda\Cms\Database\Support\Repositories;

use Illuminate\Http\Request;

abstract class AbstractRepository
{
    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function save(Request $request, $id = null)
    {
        $model = $id ? $this->model->findOrFail($id) : $this->newInstance();
        $model->fill($request->all())->save();

        return $model;
    }

    public function newInstance($attributes = [])
    {
        return $this->model->newInstance($attributes);
    }

    public function destroy($id)
    {
        $block = $this->model->find($id);
        $block->delete();

        return $block;
    }
}
