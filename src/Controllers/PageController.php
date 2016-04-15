<?php namespace Soda\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Soda\Models\Page;
use Soda\Models\Template;
use Soda\Facades\Soda;


class PageController extends PageTemplateController
{

    use    Traits\TreeableTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Page $page)
    {
        //$this->middleware('auth');
        $this->model = $page;
        $this->routeHint = 'soda.pages.';
        view()->share('routeHint', $this->routeHint);
    }


    /**
     * Show the page.
     *
     * @return Response
     */
    public function getIndex()
    {

        if (!isset($id) || !$id || $id == '#') {
            $page = $this->model->getRoots()->first();    //todo: from application.
        } elseif ($id) {
            $page = $this->model->where('id', $id)->first();
        } elseif ($request->input('id')) {
            $page = $this->model->where('id', $request->input('id'))->first();
        }

        $tree = $this->htmlTree();
        $pages = $page->collectDescendants()->withoutGlobalScopes(['live'])->orderBy('position')->get()->toTree();
//      dd($pages);
//
//		if ($id) {
//			$page = Page::findOrFail($id);
//		} else {
//			$page = Page::getRoots()->first();
//		}
        return view('soda::page.index', ['pages' => $pages, 'tree' => $tree]);
    }

    public function view($id)
    {
        if ($id) {
            $page = $this->model->with('blocks.type.fields', 'type.fields')->findOrFail($id);
        } else {
            $page = $this->model->with('blocks.type.fields', 'type.fields')->getRoots()->first();
        }

        $page_table = Soda::dynamicModel('soda_' . $page->type->identifier,
            $page->type->fields->lists('field_name')->toArray())->where('page_id',$page->id)->first();

        return view('soda::page.view', ['page' => $page, 'page_table'=>$page_table]);
    }

    public function edit($parent_id = null, $id = null)
    {
        if ($id) {
            $page = $this->model->findOrFail($id);
        } else {
            $page = new Page();
        }

        $page->fill(Request::input());
        $page->save();

        return redirect()->route($this->routeHint . 'view', ['id' => $request->id])->with('success', 'page updated');
    }

    public function getMakeRoot($id)
    {
        $this->model->find($id)->makeRoot(0);
    }

    /**
     * Main page view method.
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function page($slug)
    {

        if (starts_with('/', $slug)) {
            $page = Page::where('slug', $slug)->first(); //TODO: might not really be page::
        } else {
            $page = Page::where('slug', '/' . $slug)->first();
        }

        return (\Soda\Components\Page::constructView($page, ['page' => $page]));
    }

    public function createForm($parent_id = null)
    {
        if ($parent_id) {
            $parent = $this->model->withoutGlobalScopes(['live'])->find($parent_id);
        } else {
            $parent = $this->model->getRoots()->first();
        }

        $page = $this->model;
        $page->parent_id = $parent->id;

        return view('soda::page.view', ['page' => $page]);
    }

    /**
     * create page save functions
     * @param null $parent_id
     */
    public function create(Request $request, $parent_id = null)
    {
        $page = $this->model;
        if ($parent_id) {
            $parent = $this->model->withoutGlobalScopes(['live'])->find($parent_id);
        } else {
            $parent = $this->model->getRoots()->first();
        }

        $page->name = $request->input('name');
        $page->slug = $parent->generateSlug($request->input('slug'));
        $page->status_id = 1;
        $page->view = 'soda::default.donk'; //TODO: allow for inheriting these properties.
        $page->application_id = Soda::getApplication()->id;

        $parent->addChild($page);

        $page->save();
        dd('saved.');
    }

}
