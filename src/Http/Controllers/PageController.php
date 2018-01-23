<?php

namespace Soda\Cms\Http\Controllers;

use Soda;
use Soda\Cms\Models\Page;
use Illuminate\Http\Request;
use Soda\Cms\Models\PageType;
use Soda\Cms\Http\Controllers\Traits\TreeableTrait;

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

    /**
     * Main page view method.
     *
     * @param Request $request
     * @param string  $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function page(Request $request, $slug = '/')
    {
        return Soda::getPageBuilder()->loadPageBySlug($slug)->render($request);
    }

    /**
     * Show the page.
     *
     * @return Response
     */
    public function getIndex(Request $request)
    {
        if ($request->input('id')) {
            $page = $this->model->find($request->input('id'));
        } else {
            $page = $this->model->getRoots()->first();    //todo: from application.
            if (! $page) {
                $page = Page::createRoot();
            }
        }

        $page_types = PageType::get();

        $pages = $page ? $page->collectDescendants()->orderBy('position')->with('type')->get()->toTree() : [];
        $tree = $this->htmlTree($pages, $this->hint);

        return soda_cms_view('page.index', [
            'hint'       => $this->hint,
            'pages'      => $pages,
            'tree'       => $tree,
            'page_types' => $page_types,
        ]);
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
        
        if( isset($model->edit_action) && $model->edit_action ){
            if( isset($model->package) && $model->package ) {
                return view($model->package . '::' . $model->edit_action, ['hint' => $this->hint, 'model' => $model, 'page_table' => $page_table]);
            }
            return view($model->edit_action, ['hint' => $this->hint, 'model' => $model, 'page_table' => $page_table]);
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

        if ($this->model->type && $this->model->type->fields) {
            $dyn_table = Soda::model($this->model->type->identifier)->firstOrNew(['page_id' => $this->model->id]);

            foreach ($this->model->type->fields as $field) {
                if ($request->input('settings.'.$field->field_name) !== null) {
                    $dyn_table->parseField($field, $request, 'settings');
                }
            }

            $dyn_table->save();
        }

        return redirect()->route('soda.'.$this->hint.'.view', ['id' => $request->id])->with('success', 'Page saved successfully.');
    }

    public function create(Request $request, $parentId = null)
    {
        $parent = $parentId ? $this->model->find($parentId) : $this->model->getRoots()->first();

        $this->model->parent_id = $parent ? $parent->id : null;
        $this->model->page_type_id = $request->input('page_type_id');

        if ($this->model->page_type_id) {
            $this->model->load('type.fields');
            if ($this->model->type) {
                $this->model->fill([
                    'action'           => $this->model->type->action,
                    'action_type'      => $this->model->type->action_type,
                    'edit_action'      => $this->model->type->edit_action,
                    'edit_action_type' => $this->model->type->edit_action_type,
                ]);
            }
        }

        return soda_cms_view('page.view', ['model' => $this->model, 'hint' => $this->hint]);
    }

    /**
     * create page save functions.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $parentId = $request->input('parent_id');
        $parentPage = $parentId ? $this->model->find($parentId) : $this->model->getRoots()->first();
        $pageType = PageType::with('fields')->find($request->input('page_type_id'));

        $slug = $this->model->generateSlug($request->input('slug'));
        if ($parentPage && ! starts_with($slug, $parentPage->getAttribute('slug'))) {
            $slug = $parentPage->generateSlug($request->input('slug'));
        }

        // DEFAULTS
        $this->model->fill($pageType ? [
            'package'          => $pageType->package,
            'action'           => $pageType->action,
            'action_type'      => $pageType->action_type,
            'edit_action'      => $pageType->edit_action,
            'edit_action_type' => $pageType->edit_action_type,
        ] : [
            'package'     => 'soda',
            'action'      => 'default.view',
            'action_type' => 'view',
        ]);

        $this->model->fill([
            'name'           => $request->input('name'),
            'slug'           => $slug,
            'status'         => $request->has('status') ? $request->input('status') : 0,
            'application_id' => Soda::getApplication()->id,
            'page_type_id'   => $pageType ? $pageType->id : null,
        ]);

        if ($request->has('package')) {
            $this->model->package = $request->input('package');
        }
        if ($request->has('action')) {
            $this->model->action = $request->input('action');
        }
        if ($request->has('action_type')) {
            $this->model->action_type = $request->input('action_type');
        }
        if ($request->has('edit_action')) {
            $this->model->edit_action = $request->input('edit_action');
        }
        if ($request->has('edit_action_type')) {
            $this->model->edit_action_type = $request->input('edit_action_type');
        }

        $this->model->save();

        if ($parentPage) {
            $parentPage->addChild($this->model);
        }

        if ($pageType && $pageType->fields) {
            $pageTypeModel = Soda::model($pageType->identifier)->firstOrNew(['page_id' => $this->model->id]);

            foreach ($pageType->fields as $field) {
                if ($request->input('settings.'.$field->field_name) !== null) {
                    $pageTypeModel->parseField($field, $request, 'settings');
                }
            }

            $pageTypeModel->save();
        }

        return redirect()->route('soda.page')->with('success', 'Page saved successfully.');
    }
}
