<?php

namespace Soda\Cms\Database\Pages\Interfaces;

use Illuminate\Http\Request;

interface PageBlockRepositoryInterface
{
    /**
     * @param integer $pageId
     *
     * @return void
     */
    public function attach($pageId, $blockId, $pageBlockParams = []);

    /**
     * @param integer $pageId
     * @param integer $blockId
     *
     * @return void
     */
    public function detach($pageId, $blockId);

    /**
     * @param integer $pageId
     * @param integer $blockId
     * @param integer $modelId
     */
    public function findById($pageId, $blockId, $modelId);

    /**
     * @param integer $pageId
     * @param integer $blockId
     */
    public function newInstance($pageId, $blockId);

    /**
     * @param integer $pageId
     * @param integer $blockId
     * @param integer $modelId
     */
    public function save(Request $request, $pageId, $blockId, $modelId = null);

    /**
     * @param integer $pageId
     * @param integer $blockId
     * @param integer $modelId
     */
    public function destroy($pageId, $blockId, $modelId);
}
