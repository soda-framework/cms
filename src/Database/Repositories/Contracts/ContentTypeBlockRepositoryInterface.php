<?php

namespace Soda\Cms\Database\Repositories\Contracts;

interface ContentTypeBlockRepositoryInterface
{
    /**
     * @param int   $contentTypeId
     * @param       $blockId
     * @param array $contentBlockParams
     */
    public function attach($contentTypeId, $blockId, $contentBlockParams = []);

    /**
     * @param int $contentTypeId
     * @param int $blockId
     *
     * @return void
     */
    public function detach($contentTypeId, $blockId);
}
