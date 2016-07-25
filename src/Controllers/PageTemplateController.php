<?php namespace Soda\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Soda\Models\Template;
use Soda\Facades\Soda;


class PageTemplateController extends Controller
{

    use    Traits\TreeableTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Template $page)
    {

        //$this->middleware('auth');
        $this->model = $page;
        $this->routeHint = 'soda.templates.';
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
            $page = $this->model->with('blocks.type.fields')->findOrFail($id);
        } else {
            $page = $this->model->with('blocks.type.fields')->getRoots()->first();
        }
        return view('soda::page.view', ['page' => $page]);
    }

    public function edit(Request $request, $id = null)
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
            $page = $this->model->where('slug', $slug)->first();
        } else {
            $page = $this->model->where('slug', '/' . $slug)->first();
        }

        return (\Soda\Components\Page::constructView($page, ['page' => $page]));
    }

    public function createForm(Request $request, $parent_id = null)
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
        $page->action = 'home'; //TODO: allow for inheriting these properties.
        $page->action_type = 'view'; //TODO: allow for inheriting these properties.
        $page->application_id = Soda::getApplication()->id;

        $parent->addChild($page);

        $page->save();
        dd('saved.');
    }

}
