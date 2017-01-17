<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Pages\Interfaces\PageTypeRepositoryInterface;

class PageTypeController extends BaseController
{
    protected $pageTypes;

    public function __construct(PageTypeRepositoryInterface $pageTypes)
    {
        $this->pageTypes = $pageTypes;

        $this->middleware('soda.permission:manage-page-types');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return soda_cms_view('data.pages.types.index', $this->pageTypes->getFilteredGrid(10));
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
            $pageType = $this->pageTypes->newInstance($request);
            $pageTypes = $this->pageTypes->getList();
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'page type']));
        }

        return soda_cms_view('data.pages.types.view', compact('pageType', 'pageTypes'));
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
            $pageType = $this->pageTypes->save($request);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'page type']));
        }

        return redirect()->route('soda.page-types.edit', $pageType->getKey())->with('success', trans('soda::messages.created', ['object' => 'page type']));
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
        $pageType = $this->pageTypes->findById($id);
        $pageTypes = $this->pageTypes->getList($id);
        $blockTypes = $this->pageTypes->getAvailableBlockTypes($pageType);

        if (! $pageType) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => 'page type']));
        }

        return soda_cms_view('data.pages.types.view', compact('pageType', 'pageTypes', 'blockTypes'));
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
            $pageType = $this->pageTypes->save($request, $id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => 'page type']));
        }

        return redirect()->route('soda.page-types.edit', $pageType->getKey())->with('success', trans('soda::messages.updated', ['object' => 'page type']));
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
            $this->pageTypes->destroy($id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.delete', ['object' => 'page type']));
        }

        return redirect()->route('soda.page-types.index')->with('warning', trans('soda::messages.deleted', ['object' => 'page type']));
    }
}
