<?php

namespace Soda\Cms\Http\Controllers;

use Soda;
use Illuminate\Http\Request;
use Soda\Cms\Models\BlockType;
use Soda\Cms\Http\Controllers\Traits\CrudableTrait;

class BlockTypeController extends BaseController
{
    use CrudableTrait;
    protected $hint = 'block-type';

    public function __construct(BlockType $type)
    {
        $this->model = $type;
    }

    public function edit(Request $request, $id = null)
    {
        if ($id) {
            $this->model = $this->model->findOrFail($id);
        }

        $this->model->fill($request->except('fields'));

        $this->model->application_id = Soda::getApplication()->id;
        if ($fields = $request->input('fields')) {
            $this->model->fields()->sync($fields);
        }
        $this->model->save();

        return redirect()->route('soda.'.$this->hint.'.view', ['id' => $this->model->id])->with('success',
            'updated');
    }
}
