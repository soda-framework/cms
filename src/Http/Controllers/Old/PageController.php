<?php

namespace Soda\Cms\Http\Controllers\Old;

use Illuminate\Http\Request;
use Soda;
use Soda\Cms\Http\Controllers\Traits\TreeableTrait;
use Soda\Cms\Models\Page;
use Soda\Cms\Models\PageType;
use SodaMatcher;

class PageController extends BaseController
{
    use TreeableTrait;
    public $hint = 'page';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Page $page)
    {
        //$this->middleware('auth');
        $this->model = $page;
        $this->tree = $page;
    }

    public function view($id)
    {
        if ($id) {
            $model = $this->model->with('blocks.type.fields', 'type.fields')->findOrFail($id);
        } else {
            $model = $this->model->with('blocks.type.fields', 'type.fields')->getRoots()->first();
        }
        if (@$model->type->identifier) {
            $page_table = Soda::dynamicModel('soda_'.$model->type->identifier,
                $model->type->fields->pluck('field_name')->toArray())->where('page_id', $model->id)->first();
        } else {
            $page_table = null;
        }

        return soda_cms_view('page.view', ['hint' => $this->hint, 'model' => $model, 'page_table' => $page_table]);
    }

    public function edit(Request $request, $id = null)
    {
        if ($id) {
            $this->model = $this->model->findOrFail($id);
        }

        $this->model->fill($request->all());
        $this->model->save();

        //we also need to save the settings - careful here..
        $this->model->load('type.fields');

        if ($request->has('settings')) {

            $dyn_table = Soda::dynamicModel('soda_'.$this->model->type->identifier,
                $this->model->type->fields->pluck('field_name')->toArray())->where('page_id', $this->model->id)->first();

            $dyn_table->forceFill($request->input('settings'));

            $dyn_table->save();
        }

        return redirect()->route('soda.'.$this->hint.'.view', ['id' => $request->id])->with('success', 'page updated');
    }

    public function create(Request $request, $parent_id = null)
    {
        $parent = $parent_id ? $this->model->find($parent_id) : $this->model->getRoots()->first();

        $this->model->parent_id = $parent ? $parent->id : null;
        $this->model->page_type_id = $request->input('page_type_id');

        if ($this->model->page_type_id) {
            $this->model->load('type.fields');
            $this->model->action = $this->model->type->action;
            $this->model->action_type = $this->model->type->action_type;
            $this->model->edit_action = $this->model->type->edit_action;
            $this->model->edit_action_type = $this->model->type->edit_action_type;
        }

        return soda_cms_view('page.view', ['model' => $this->model, 'hint' => $this->hint]);
    }

    /**
     * create page save functions
     *
     * @param null $parent_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $page = $this->model;
        $parent_id = $request->input('parent_id');

        $parent = $parent_id ? $this->model->find($parent_id) : $this->model->getRoots()->first();

        //todo validation

        $page->fill([
            'name'           => $request->input('name'),
            'slug'           => $parent ? $parent->generateSlug($request->input('slug')) : $page->generateSlug($request->input('slug')),
            'status'         => $request->has('status') ? $request->input('status') : 0,
            'action_type'    => $request->has('action_type') ? $request->input('action_type') : 'view',
            'package'        => $request->has('package') ? $request->input('package') : 'soda',
            'action'         => $request->has('action') ? $request->input('action') : 'default.view',
            'application_id' => Soda::getApplication()->id,
            'page_type_id'   => $request->input('page_type_id'),
        ]);

        $page->save();

        if ($parent) {
            $parent->addChild($page);
        }

        if ($page->type) {
            if ($request->has('settings')) {
                $dyn_table = Soda::model($page->type->identifier)->where('page_id', $page->id)->first();
                $dyn_table->fill($request->input('settings'));
                $dyn_table->save();
            }
        }

        return redirect()->route('soda.page')->with('success', 'Page saved successfully.');
    }

}