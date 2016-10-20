<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Pages\Interfaces\PageRepositoryInterface;

class PageController extends BaseController
{
    protected $pages;

    public function __construct(PageRepositoryInterface $pages)
    {
        $this->pages = $pages;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return soda_cms_view('data.pages.index', [
            'pages'      => $this->pages->getTree(),
            'page_types' => $this->pages->getTypes(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = $this->pages->findById($id);

        if(!$page) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => 'page']));
        }

        return view($page->edit_action, compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $page = $this->pages->destroy($id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.delete', ['object' => 'page']));
        }

        return redirect()->route('soda.pages.edit', $page->getKey())->with('warning', trans('soda::messages.deleted', ['object' => 'page']));
    }
}
