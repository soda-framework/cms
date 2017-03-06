<?php

namespace Soda\Cms\Database\Pages\Interfaces;

interface PageTypeBlockRepositoryInterface
{
    /**
     * @param int   $pageTypeId
     *
     * @param       $blockId
     * @param array $pageBlockParams
     */
    public function attach($pageTypeId, $blockId, $pageBlockParams = []);

    /**
     * @param int $pageTypeId
     * @param int $blockId
     *
     * @return void
     */
    public function detach($pageTypeId, $blockId);
}
