<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Repositories\Contracts\PageRepositoryInterface;

class PageController extends BaseController
{
    protected $pages;

    public function __construct(PageRepositoryInterface $pages)
    {
        $this->pages = $pages;

        $this->middleware('soda.permission:view-pages');
        $this->middleware('soda.permission:create-pages')->only(['create', 'store']);
        $this->middleware('soda.permission:edit-pages')->only(['edit', 'update']);
        $this->middleware('soda.permission:delete-pages')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $pages = $this->pages->getTree();

        $page_types = $this->pages->getTypes(true);
        $page_types->load('subpageTypes');

        return soda_cms_view('data.pages.index', compact('pages', 'page_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        try {
            $parentId = $request->input('parentId');
            $pageTypeId = $request->input('pageTypeId');
            $page = $this->pages->createStub($parentId, $pageTypeId);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'page']));
        }

        return view($page->edit_action, compact('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        try {
            $page = $this->pages->save($request);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'page']));
        }

        return redirect()->route('soda.pages.edit', $page->getKey())->with('success', trans('soda::messages.created', ['object' => 'page']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $page = $this->pages->findById($id);

        if (! $page) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => 'page']));
        }

        $page->load('blockTypes.fields', 'type.blockTypes.fields', 'type.fields');
        $blockTypes = $this->pages->getAvailableBlockTypes($page);

        return view($page->edit_action, compact('page', 'blockTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $page = $this->pages->save($request, $id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => 'page']));
        }

        return redirect()->route('soda.pages.edit', $page->getKey())->with('success', trans('soda::messages.updated', ['object' => 'page']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        try {
            $this->pages->destroy($id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.delete', ['object' => 'page']));
        }

        return redirect()->route('soda.pages.index')->with('warning', trans('soda::messages.deleted', ['object' => 'page']));
    }
}
