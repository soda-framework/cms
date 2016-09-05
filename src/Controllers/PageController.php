<?php namespace Soda\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Soda\Cms\Controllers\Traits\TreeableTrait;
use Soda;
use Soda\Cms\Models\Page;
use Soda\Cms\Models\PageType;

class PageController extends Controller {
    use TreeableTrait;
    public $hint = 'page';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Page $page) {
        //$this->middleware('auth');
        $this->model = $page;
        $this->tree = $page;
    }


    /**
     * Show the page.
     *
     * @return Response
     */
    public function getIndex(Request $request) {
        if ($request->input('id')) {
            $page = $this->model->find($request->input('id'));
        } else {
            $page = $this->model->getRoots()->first();    //todo: from application.
            if(!$page) $page = Page::createRoot();
        }

        $page_types = PageType::get();

        $pages = $page ? $page->collectDescendants()->orderBy('position')->get()->toTree() : [];
        $tree = $this->htmlTree($pages, $this->hint);

        return view('soda::page.index', [
            'hint'       => $this->hint,
            'pages'      => $pages,
            'tree'       => $tree,
            'page_types' => $page_types,
        ]);
    }

    public function view($id) {
        if ($id) {
            $model = $this->model->with('blocks.type.fields', 'type.fields')->findOrFail($id);
        } else {
            $model = $this->model->with('blocks.type.fields', 'type.fields')->getRoots()->first();
        }
        if (@$model->type->identifier) {
            $page_table = Soda::dynamicModel('soda_' . $model->type->identifier,
                $model->type->fields->lists('field_name')->toArray())->where('page_id', $model->id)->first();
        } else {
            $page_table = null;
        }

        return view('soda::page.view', ['hint' => $this->hint, 'model' => $model, 'page_table' => $page_table]);
    }

    public function edit(Request $request, $id = null) {
        if($id) {
            $this->model = $this->model->findOrFail($id);
        }

        if(!$request->has('status')) $request->merge(['status' => 0]);

        $this->model->fill($request->all());
        $this->model->save();

        //we also need to save the settings - careful here..
        $this->model->load('type.fields');

        if ($request->has('settings')) {

            $dyn_table = Soda::dynamicModel('soda_' . $this->model->type->identifier,
                $this->model->type->fields->lists('field_name')->toArray())->where('page_id', $this->model->id)->first();

            $dyn_table->forceFill($request->input('settings'));

            $dyn_table->save();
        }

        return redirect()->route('soda.' . $this->hint . '.view', ['id' => $request->id])->with('success', 'page updated');
    }

    /**
     * Main page view method.
     *
     * @param $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function page($slug) {
        return Soda::getPageBuilder()->loadPageBySlug($slug)->render();
    }

    public function create(Request $request, $parent_id = null) {
        $parent = $parent_id ? $this->model->find($parent_id) : $this->model->getRoots()->first();

        $this->model->parent_id = $parent ? $parent->id : null;
        $this->model->page_type_id = $request->input('page_type_id');

        if($this->model->page_type_id) {
            $this->model->load('type.fields');
        }

        return view('soda::page.view', ['model' => $this->model, 'hint' => $this->hint]);
    }

    /**
     * create page save functions
     *
     * @param null $parent_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request) {
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

        if($parent) {
            $parent->addChild($page);
        }

        if($page->type) {
            $dyn_table = Soda::dynamicModel('soda_' . $page->type->identifier,
                $page->type->fields->lists('field_name')->toArray())->newInstance();

            $dyn_table->page_id = $page->id;

            if ($request->has('settings')) {
                $dyn_table->forceFill($request->input('settings'));
            }

            $dyn_table->save();
        }

        return redirect()->route('soda.page')->with('success', 'Page saved successfully.');
    }

}
