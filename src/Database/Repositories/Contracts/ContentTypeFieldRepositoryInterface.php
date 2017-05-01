<?php

namespace Soda\Cms\Database\Repositories\Contracts;

interface ContentTypeFieldRepositoryInterface
{
    /**
     * @param int   $contentTypeId
     *
     * @param       $fieldId
     * @param array $contentBlockParams
     */
    public function attach($contentTypeId, $fieldId, $contentBlockParams = []);

    /**
     * @param int $contentTypeId
     * @param int $fieldId
     *
     * @return void
     */
    public function detach($contentTypeId, $fieldId);
}
