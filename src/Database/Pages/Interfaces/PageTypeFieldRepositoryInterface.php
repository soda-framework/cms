<?php

namespace Soda\Cms\Database\Pages\Interfaces;

interface PageTypeFieldRepositoryInterface
{
    /**
     * @param int   $pageTypeId
     *
     * @param       $fieldId
     * @param array $pageFieldParams
     */
    public function attach($pageTypeId, $fieldId, $pageFieldParams = []);

    /**
     * @param int $pageTypeId
     * @param int $fieldId
     *
     * @return void
     */
    public function detach($pageTypeId, $fieldId);
}
