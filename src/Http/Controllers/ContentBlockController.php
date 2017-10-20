<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Models\Contracts\BlockTypeInterface;
use Soda\Cms\Database\Repositories\Contracts\ContentBlockRepositoryInterface;

class ContentBlockController extends BaseController
{
    protected $contentBlocks;

    public function __construct(ContentBlockRepositoryInterface $contentBlocks)
    {
        $this->contentBlocks = $contentBlocks;

        $this->middleware(function ($request, $next) {
            app('soda.interface')->breadcrumbs()->addLink(route('soda.home'), ucfirst(trans('soda::terminology.home')));

            return $next($request);
        });

        $this->middleware('soda.permission:view-pages');
        $this->middleware('soda.permission:edit-pages')->only(['create', 'store', 'delete', 'edit', 'update']);
        $this->middleware('soda.permission:attach-blocks')->only(['attach']);
        $this->middleware('soda.permission:detach-blocks')->only(['detach']);
    }

    /**
     * Attaches a new resource.
     *
     * @param Request $request
     * @param  int    $contentId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attach(Request $request, $contentId)
    {
        try {
            $this->contentBlocks->attach($contentId, $request->input('block_id'), array_filter($request->only('min_blocks', 'max_blocks')));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.attach', ['object' => trans('soda::terminology.block')]));
        }

        return redirect()->route('soda.content.edit', $contentId)->with('success', trans('soda::messages.attached', ['object' => trans('soda::terminology.block')]));
    }

    /**
     * Detaches an existing resource.
     *
     * @param  int $contentId
     * @param  int $blockTypeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detach($contentId, $blockTypeId)
    {
        try {
            $this->contentBlocks->detach($contentId, $blockTypeId);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.detach', ['object' => trans('soda::terminology.block')]));
        }

        return redirect()->route('soda.content.edit', $contentId)->with('success', trans('soda::messages.detached', ['object' => trans('soda::terminology.block')]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int $contentId
     * @param  int $blockTypeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create($contentId, $blockTypeId)
    {
        try {
            list($content, $blockType, $block) = array_values($this->contentBlocks->newInstance($contentId, $blockTypeId));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => trans('soda::terminology.block')]));
        }

        app('soda.interface')->setHeading('New '.$blockType->name);
        app('soda.interface')->breadcrumbs()->addLink(route('soda.content.index'), ucfirst(trans('soda::terminology.content_plural')));
        app('soda.interface')->breadcrumbs()->addLink(route('soda.content.edit', $content->id), $content->name);
        app('soda.interface')->breadcrumbs()->addLink(route('soda.content.edit', $content->id).'?tab='.strtolower($blockType->identifier), $blockType->name);

        return soda_cms_view('data.content.blocks.view', compact('content', 'blockType', 'block'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $contentId
     * @param  int                      $blockTypeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request, $contentId, $blockTypeId)
    {
        try {
            list($content, $blockType, $block) = array_values($this->contentBlocks->save($request, $contentId, $blockTypeId));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => trans('soda::terminology.block')]));
        }

        return redirect()->route('soda.content.edit', [$contentId, 'tab' => $this->getTab($blockType)])->with('success', trans('soda::messages.created', ['object' => trans('soda::terminology.block')]));
    }

    protected function getTab(BlockTypeInterface $blockType)
    {
        return strtolower($blockType->identifier);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $contentId
     * @param  int $blockTypeId
     * @param  int $blockId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit($contentId, $blockTypeId, $blockId)
    {
        list($content, $blockType, $block) = array_values($this->contentBlocks->findById($contentId, $blockTypeId, $blockId));

        if (! $block) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => trans('soda::terminology.block')]));
        }

        app('soda.interface')->setHeading('Editing block');
        app('soda.interface')->breadcrumbs()->addLink(route('soda.content.index'), ucfirst(trans('soda::terminology.content_plural')));
        app('soda.interface')->breadcrumbs()->addLink(route('soda.content.edit', $content->id), $content->name);
        app('soda.interface')->breadcrumbs()->addLink(route('soda.content.edit', $content->id).'?tab='.strtolower($blockType->identifier), $blockType->name);

        return soda_cms_view('data.content.blocks.view', compact('content', 'blockType', 'block'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $contentId
     * @param  int                      $blockTypeId
     * @param  int                      $blockId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $contentId, $blockTypeId, $blockId)
    {
        try {
            list($content, $blockType, $block) = array_values($this->contentBlocks->save($request, $contentId, $blockTypeId, $blockId));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => trans('soda::terminology.block')]));
        }

        return redirect()->route('soda.content.edit', [$contentId, 'tab' => $this->getTab($blockType)])->with('success', trans('soda::messages.updated', ['object' => trans('soda::terminology.block')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $contentId
     * @param  int $blockTypeId
     * @param  int $blockId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($contentId, $blockTypeId, $blockId)
    {
        try {
            list($content, $blockType, $block) = array_values($this->contentBlocks->destroy($contentId, $blockTypeId, $blockId));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.delete', ['object' => trans('soda::terminology.block')]));
        }

        return redirect()->route('soda.content.edit', [$contentId, 'tab' => $this->getTab($blockType)])->with('warning', trans('soda::messages.deleted', ['object' => trans('soda::terminology.block')]));
    }
}
