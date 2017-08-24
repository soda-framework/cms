<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Repositories\Contracts\BlockTypeRepositoryInterface;

class BlockTypeController extends BaseController
{
    protected $blockTypes;

    public function __construct(BlockTypeRepositoryInterface $blockTypes)
    {
        $this->blockTypes = $blockTypes;

        $this->middleware(function ($request, $next) {
            app('soda.interface')->setHeading(ucwords(trans('soda::terminology.block_type_plural')))->setHeadingIcon('mdi mdi mdi-widgets');
            app('soda.interface')->breadcrumbs()->addLink(route('soda.home'), ucfirst(trans('soda::terminology.home')));

            return $next($request);
        });

        $this->middleware('soda.permission:manage-block-types');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        app('soda.interface')->setDescription('Different '.ucwords(trans('soda::terminology.block_type_plural')).' have different field types applied to them'); // TODO: translate

        return soda_cms_view('data.block-types.index', $this->blockTypes->getFilteredGrid(10));
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
        app('soda.interface')->setHeading('New '.ucwords(trans('soda::terminology.block_type_plural')));
        app('soda.interface')->breadcrumbs()->addLink(route('soda.block-types.index'), ucwords(trans('soda::terminology.block_type_plural')));

        try {
            $blockType = $this->blockTypes->newInstance($request);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => trans('soda::terminology.block_type')]));
        }

        return soda_cms_view('data.block-types.view', compact('blockType'));
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
            $blockType = $this->blockTypes->save($request);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => trans('soda::terminology.block_type')]));
        }

        return redirect()->route('soda.block-types.edit', $blockType->getKey())->with('success', trans('soda::messages.created', ['object' => trans('soda::terminology.block_type')]));
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
        $blockType = $this->blockTypes->findById($id);
        $fields = $this->blockTypes->getAvailableFields($blockType);

        if (! $blockType) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => trans('soda::terminology.block_type')]));
        }

        app('soda.interface')->setHeading($blockType->name);
        app('soda.interface')->breadcrumbs()->addLink(route('soda.block-types.index'), ucwords(trans('soda::terminology.block_type_plural')));

        return soda_cms_view('data.block-types.view', compact('blockType', 'fields'));
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
            $blockType = $this->blockTypes->save($request, $id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => trans('soda::terminology.block_type')]));
        }

        return redirect()->route('soda.block-types.edit', $blockType->getKey())->with('success', trans('soda::messages.updated', ['object' => trans('soda::terminology.block_type')]));
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
            $this->blockTypes->destroy($id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.delete', ['object' => trans('soda::terminology.block_type')]));
        }

        return redirect()->route('soda.block-types.index')->with('warning', trans('soda::messages.deleted', ['object' => trans('soda::terminology.block_type')]));
    }
}
