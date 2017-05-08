<?php

namespace Soda\Cms\Database\Repositories\Contracts;

use Illuminate\Http\Request;

interface ContentBlockRepositoryInterface
{
    /**
     * @param       $contentId
     * @param       $blockId
     * @param array $contentBlockParams
     */
    public function attach($contentId, $blockId, $contentBlockParams = []);

    /**
     * @param int $contentId
     * @param int $blockId
     *
     * @return void
     */
    public function detach($contentId, $blockId);

    /**
     * @param int $contentId
     * @param int $blockId
     * @param int $modelId
     */
    public function findById($contentId, $blockId, $modelId);

    /**
     * @param int $contentId
     * @param int $blockId
     */
    public function newInstance($contentId, $blockId);

    /**
     * @param Request $request
     * @param int     $contentId
     * @param int     $blockId
     * @param int     $modelId
     */
    public function save(Request $request, $contentId, $blockId, $modelId = null);

    /**
     * @param int $contentId
     * @param int $blockId
     * @param int $modelId
     */
    public function destroy($contentId, $blockId, $modelId);
}
