<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Repositories\Contracts\PageTypeBlockRepositoryInterface;

class PageTypeBlockController extends BaseController
{
    protected $pageTypeBlocks;

    public function __construct(PageTypeBlockRepositoryInterface $pageTypeBlocks)
    {
        $this->pageTypeBlocks = $pageTypeBlocks;
    }

    /**
     * Attaches a new resource.
     *
     * @param Request $request
     * @param  int    $pageTypeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attach(Request $request, $pageTypeId)
    {
        try {
            $this->pageTypeBlocks->attach($pageTypeId, $request->input('block_id'), array_filter($request->only('min_blocks', 'max_blocks')));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.attach', ['object' => 'block']));
        }

        return redirect()->route('soda.page-types.edit', [$pageTypeId, 'tab' => 'blocks'])->with('success', trans('soda::messages.attached', ['object' => 'block']));
    }

    /**
     * Detaches an existing resource.
     *
     * @param  int    $pageTypeId
     * @param  int    $blockTypeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detach($pageTypeId, $blockTypeId)
    {
        try {
            $this->pageTypeBlocks->detach($pageTypeId, $blockTypeId);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.detach', ['object' => 'block']));
        }

        return redirect()->route('soda.page-types.edit', [$pageTypeId, 'tab' => 'blocks'])->with('success', trans('soda::messages.detached', ['object' => 'block']));
    }
}
