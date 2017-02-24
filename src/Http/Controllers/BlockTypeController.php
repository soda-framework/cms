<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeRepositoryInterface;

class BlockTypeController extends BaseController
{
    protected $blockTypes;

    public function __construct(BlockTypeRepositoryInterface $blockTypes)
    {
        $this->blockTypes = $blockTypes;

        $this->middleware('soda.permission:manage-block-types');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->soda_cms_view('data.blocks.types.index', $this->blockTypes->getFilteredGrid(10));
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
            $blockType = $this->blockTypes->newInstance($request);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'block type']));
        }

        return soda_cms_view('data.blocks.types.view', compact('blockType'));
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
            $blockType = $this->blockTypes->save($request);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'block type']));
        }

        return redirect()->route('soda.block-types.edit', $blockType->getKey())->with('success', trans('soda::messages.created', ['object' => 'block type']));
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
        $blockType = $this->blockTypes->findById($id);

        if (! $blockType) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => 'block type']));
        }

        return soda_cms_view('data.blocks.types.view', compact('blockType'));
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
            $blockType = $this->blockTypes->save($request, $id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => 'block type']));
        }

        return redirect()->route('soda.block-types.edit', $blockType->getKey())->with('success', trans('soda::messages.updated', ['object' => 'block type']));
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
            $this->blockTypes->destroy($id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.delete', ['object' => 'block type']));
        }

        return redirect()->route('soda.block-types.index')->with('warning', trans('soda::messages.deleted', ['object' => 'block type']));
    }
}
