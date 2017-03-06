<?php

namespace Soda\Cms\Database\Blocks\Interfaces;

interface BlockTypeFieldRepositoryInterface
{
    /**
     * @param int   $blockTypeId
     *
     * @param       $fieldId
     * @param array $blockFieldParams
     */
    public function attach($blockTypeId, $fieldId, $blockFieldParams = []);

    /**
     * @param int $blockTypeId
     * @param int $fieldId
     *
     * @return void
     */
    public function detach($blockTypeId, $fieldId);
}
