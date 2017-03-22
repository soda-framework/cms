<?php

namespace Soda\Cms\Database\Repositories\Contracts;

use Illuminate\Http\Request;

interface PageBlockRepositoryInterface
{
    /**
     * @param int $pageId
     *
     * @return void
     */
    public function attach($pageId, $blockId, $pageBlockParams = []);

    /**
     * @param int $pageId
     * @param int $blockId
     *
     * @return void
     */
    public function detach($pageId, $blockId);

    /**
     * @param int $pageId
     * @param int $blockId
     * @param int $modelId
     */
    public function findById($pageId, $blockId, $modelId);

    /**
     * @param int $pageId
     * @param int $blockId
     */
    public function newInstance($pageId, $blockId);

    /**
     * @param int $pageId
     * @param int $blockId
     * @param int $modelId
     */
    public function save(Request $request, $pageId, $blockId, $modelId = null);

    /**
     * @param int $pageId
     * @param int $blockId
     * @param int $modelId
     */
    public function destroy($pageId, $blockId, $modelId);
}
