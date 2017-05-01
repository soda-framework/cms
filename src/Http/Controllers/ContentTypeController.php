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

        app('soda.interface')->setHeading('Content Types')->setHeadingIcon('mdi mdi-file-outline');
        app('soda.interface')->breadcrumbs()->addLink(route('soda.home'), 'Home');

        $this->middleware('soda.permission:manage-content-types');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        app('soda.interface')->setDescription('Different Content Types have different field types applied to them');

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
        app('soda.interface')->setHeading('New Content Type');
        app('soda.interface')->breadcrumbs()->addLink(route('soda.content-types.index'), 'Content Types');

        try {
            $contentType = $this->contentTypes->newInstance($request);
            $contentTypes = $this->contentTypes->getList();
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'content type']));
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
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'content type']));
        }

        return redirect()->route('soda.content-types.edit', $contentType->getKey())->with('success', trans('soda::messages.created', ['object' => 'content type']));
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
            return $this->handleError(trans('soda::errors.not-found', ['object' => 'content type']));
        }

        app('soda.interface')->breadcrumbs()->addLink(route('soda.content-types.index'), 'Content Types');
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
            return $this->handleException($e, trans('soda::errors.update', ['object' => 'content type']));
        }

        return redirect()->route('soda.content-types.edit', $contentType->getKey())->with('success', trans('soda::messages.updated', ['object' => 'content type']));
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
            return $this->handleException($e, trans('soda::errors.delete', ['object' => 'content type']));
        }

        return redirect()->route('soda.content-types.index')->with('warning', trans('soda::messages.deleted', ['object' => 'content type']));
    }
}
