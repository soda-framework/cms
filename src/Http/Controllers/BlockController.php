<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Soda\Cms\Database\Blocks\Interfaces\BlockRepositoryInterface;

class BlockController extends BaseController
{
    protected $blocks;

    public function __construct(BlockRepositoryInterface $blocks)
    {
        $this->blocks = $blocks;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return soda_cms_view('data.blocks.index', array_merge($this->blocks->getFilteredGrid(10), [
            'blockTypes' => $this->blocks->getTypes(),
        ]));
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
            $blockTypeId = $request->input('blockTypeId');
            $block = $this->blocks->createStub($blockTypeId);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'block']));
        }

        return soda_cms_view('data.blocks.view', compact('block'));
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
            $block = $this->blocks->save($request);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'block']));
        }

        return redirect()->route('soda.blocks.edit', $block->getKey())->with('success', trans('soda::messages.created', ['object' => 'block']));
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
        $block = $this->blocks->findById($id);

        if(!$block) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => 'block']));
        }

        return soda_cms_view('data.blocks.view', compact('block'));
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
            $block = $this->blocks->save($request, $id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => 'block']));
        }

        return redirect()->route('soda.blocks.edit', $block->getKey())->with('success', trans('soda::messages.updated', ['object' => 'block']));
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
            $block = $this->blocks->destroy($id);
        }
        catch ( ModelNotFoundException $e )
        {
            return $this->handleException($e, trans('soda::errors.not-found', ['object' => 'block']));
        }
        catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.delete', ['object' => 'block']));
        }

        return redirect()->route('soda.blocks.edit', $block->getKey())->with('warning', trans('soda::messages.deleted', ['object' => 'block']));
    }
}
