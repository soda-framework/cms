<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Repositories\Contracts\ContentTypeRepositoryInterface;

class ContentTypeController extends BaseController
{
    protected $contentTypes;

    public function __construct(ContentTypeRepositoryInterface $contentTypes)
    {
        $this->contentTypes = $contentTypes;

        app('soda.interface')->setHeading(ucwords(trans('soda::terminology.content_type_plural')))->setHeadingIcon('mdi mdi-file-outline');
        app('soda.interface')->breadcrumbs()->addLink(route('soda.home'), ucfirst(trans('soda::terminology.home')));

        $this->middleware('soda.permission:manage-content-types');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        app('soda.interface')->setDescription('Different ' . ucwords(trans('soda::terminology.content_type_plural')) . ' have different field types applied to them');

        return soda_cms_view('data.content.types.index', $this->contentTypes->getFilteredGrid(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        app('soda.interface')->setHeading('New ' . ucwords(trans('soda::terminology.content_type')));
        app('soda.interface')->breadcrumbs()->addLink(route('soda.content-types.index'), ucwords(trans('soda::terminology.content_type_plural')));

        try {
            $contentType = $this->contentTypes->newInstance($request);
            $contentTypes = $this->contentTypes->getList();
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => trans('soda::terminology.content_type')]));
        }

        return soda_cms_view('data.content.types.view', compact('contentType', 'contentTypes'));
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
            $contentType = $this->contentTypes->save($request);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => trans('soda::terminology.content_type')]));
        }

        return redirect()->route('soda.content-types.edit', $contentType->getKey())->with('success', trans('soda::messages.created', ['object' => trans('soda::terminology.content_type')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit($id)
    {
        $contentType = $this->contentTypes->findById($id);
        $contentTypes = $this->contentTypes->getList($id);
        $blockTypes = $this->contentTypes->getAvailableBlockTypes($contentType);
        $fields = $this->contentTypes->getAvailableFields($contentType);

        if (! $contentType) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => trans('soda::terminology.content_type')]));
        }

        app('soda.interface')->breadcrumbs()->addLink(route('soda.content-types.index'), ucwords(trans('soda::terminology.content_type_plural')));
        app('soda.interface')->setHeading($contentType->name);

        return soda_cms_view('data.content.types.view', compact('contentType', 'contentTypes', 'blockTypes', 'fields'));
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
            $contentType = $this->contentTypes->save($request, $id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => trans('soda::terminology.content_type')]));
        }

        return redirect()->route('soda.content-types.edit', $contentType->getKey())->with('success', trans('soda::messages.updated', ['object' => trans('soda::terminology.content_type')]));
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
            $this->contentTypes->destroy($id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.delete', ['object' => trans('soda::terminology.content_type')]));
        }

        return redirect()->route('soda.content-types.index')->with('warning', trans('soda::messages.deleted', ['object' => trans('soda::terminology.content_type')]));
    }
}
