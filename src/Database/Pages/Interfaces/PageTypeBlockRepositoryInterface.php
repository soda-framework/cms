<?php

namespace Soda\Cms\Database\Pages\Interfaces;

interface PageTypeBlockRepositoryInterface
{
    /**
     * @param integer $pageTypeId
     *
     * @return void
     */
    public function attach($pageTypeId, $blockId, $pageBlockParams = []);

    /**
     * @param integer $pageTypeId
     * @param integer $blockId
     *
     * @return void
     */
    public function detach($pageTypeId, $blockId);
}
