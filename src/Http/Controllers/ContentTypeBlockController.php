<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Repositories\Contracts\ContentTypeBlockRepositoryInterface;

class ContentTypeBlockController extends BaseController
{
    protected $contentTypeBlocks;

    public function __construct(ContentTypeBlockRepositoryInterface $contentTypeBlocks)
    {
        $this->contentTypeBlocks = $contentTypeBlocks;
    }

    /**
     * Attaches a new resource.
     *
     * @param Request $request
     * @param  int    $contentTypeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attach(Request $request, $contentTypeId)
    {
        try {
            $this->contentTypeBlocks->attach($contentTypeId, $request->input('block_id'), array_filter($request->only('min_blocks', 'max_blocks')));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.attach', ['object' => trans('soda::terminology.block')]));
        }

        return redirect()->route('soda.content-types.edit', [$contentTypeId, 'tab' => 'blocks'])->with('success', trans('soda::messages.attached', ['object' => trans('soda::terminology.block')]));
    }

    /**
     * Detaches an existing resource.
     *
     * @param  int    $contentTypeId
     * @param  int    $blockTypeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detach($contentTypeId, $blockTypeId)
    {
        try {
            $this->contentTypeBlocks->detach($contentTypeId, $blockTypeId);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.detach', ['object' => trans('soda::terminology.block')]));
        }

        return redirect()->route('soda.content-types.edit', [$contentTypeId, 'tab' => 'blocks'])->with('success', trans('soda::messages.detached', ['object' => trans('soda::terminology.block')]));
    }
}
