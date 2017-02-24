<?php

namespace Soda\Cms\Database\Pages\Interfaces;

interface PageTypeBlockRepositoryInterface
{
    /**
     * @param int $pageTypeId
     *
     * @return void
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
