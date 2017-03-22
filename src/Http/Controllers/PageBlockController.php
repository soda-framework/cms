<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Repositories\Contracts\BlockTypeInterface;
use Soda\Cms\Database\Repositories\Contracts\PageBlockRepositoryInterface;

class PageBlockController extends BaseController
{
    protected $pageBlocks;

    public function __construct(PageBlockRepositoryInterface $pageBlocks)
    {
        $this->pageBlocks = $pageBlocks;

        $this->middleware('soda.permission:view-pages');
        $this->middleware('soda.permission:edit-pages')->only(['create', 'store', 'delete', 'edit', 'update']);
        $this->middleware('soda.permission:attach-blocks')->only(['attach']);
        $this->middleware('soda.permission:detach-blocks')->only(['detach']);
    }

    /**
     * Attaches a new resource.
     *
     * @param Request $request
     * @param  int    $pageId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attach(Request $request, $pageId)
    {
        try {
            $this->pageBlocks->attach($pageId, $request->input('block_id'), array_filter($request->only('min_blocks', 'max_blocks')));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.attach', ['object' => 'block']));
        }

        return redirect()->route('soda.pages.edit', $pageId)->with('success', trans('soda::messages.attached', ['object' => 'block']));
    }

    /**
     * Detaches an existing resource.
     *
     * @param  int    $pageId
     * @param  int    $blockTypeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detach($pageId, $blockTypeId)
    {
        try {
            $this->pageBlocks->detach($pageId, $blockTypeId);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.detach', ['object' => 'block']));
        }

        return redirect()->route('soda.pages.edit', $pageId)->with('success', trans('soda::messages.detached', ['object' => 'block']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int $pageId
     * @param  int $blockTypeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create($pageId, $blockTypeId)
    {
        try {
            list($page, $blockType, $block) = array_values($this->pageBlocks->newInstance($pageId, $blockTypeId));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'block']));
        }

        return soda_cms_view('data.blocks.view', compact('page', 'blockType', 'block'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $pageId
     * @param  int                      $blockTypeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request, $pageId, $blockTypeId)
    {
        try {
            list($page, $blockType, $block) = array_values($this->pageBlocks->save($request, $pageId, $blockTypeId));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'block']));
        }

        return redirect()->route('soda.pages.edit', [$pageId, 'tab' => $this->getTab($blockType)])->with('success', trans('soda::messages.created', ['object' => 'block']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $pageId
     * @param  int $blockTypeId
     * @param  int $blockId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit($pageId, $blockTypeId, $blockId)
    {
        list($page, $blockType, $block) = array_values($this->pageBlocks->findById($pageId, $blockTypeId, $blockId));

        if (! $block) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => 'block']));
        }

        return soda_cms_view('data.blocks.view', compact('page', 'blockType', 'block'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $pageId
     * @param  int                      $blockTypeId
     * @param  int                      $blockId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $pageId, $blockTypeId, $blockId)
    {
        try {
            list($page, $blockType, $block) = array_values($this->pageBlocks->save($request, $pageId, $blockTypeId, $blockId));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => 'block']));
        }

        return redirect()->route('soda.pages.edit', [$pageId, 'tab' => $this->getTab($blockType)])->with('success', trans('soda::messages.updated', ['object' => 'block']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $pageId
     * @param  int $blockTypeId
     * @param  int $blockId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($pageId, $blockTypeId, $blockId)
    {
        try {
            list($page, $blockType, $block) = array_values($this->pageBlocks->destroy($pageId, $blockTypeId, $blockId));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.delete', ['object' => 'block']));
        }

        return redirect()->route('soda.pages.edit', [$pageId, 'tab' => $this->getTab($blockType)])->with('warning', trans('soda::messages.deleted', ['object' => 'block']));
    }

    protected function getTab(BlockTypeInterface $blockType)
    {
        return strtolower($blockType->identifier);
    }
}
