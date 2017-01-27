<?php

namespace Soda\Cms\Http\Controllers;

use Soda;
use Soda\Cms\Models\Page;
use Illuminate\Http\Request;
use Soda\Cms\Models\PageType;
use Soda\Cms\Http\Controllers\Traits\CrudableTrait;

class PageTypeController extends BaseController
{
    use CrudableTrait;
    public $hint = 'page-type';

    public function __construct(PageType $type)
    {
        $this->model = $type;
    }

    public function edit(Request $request, $id = null)
    {
        if ($id) {
            $this->model = $this->model->findOrFail($id);
        }

        $this->model->fill($request->input());
        $this->model->application_id = Soda::getApplication()->id;

        if ($this->model->id && $this->model->isDirty('allowed_children')) {
            Page::where('page_type_id', $this->model->id)->update(['allowed_children' => $this->model->allowed_children]);
        }

        $this->model->save();

        return redirect()->route($this->getRouteTo('view'), [
            'id' => $this->model->id,
        ])->with('success', 'updated');
    }
}
