<?php

namespace Soda\Cms\Database\Repositories\Contracts;

use Soda\Cms\Database\Models\Contracts\ContentTypeInterface;

interface ContentTypeRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getBlockTypes();

    public function getAvailableBlockTypes(ContentTypeInterface $contentType);

    public function getFields();

    public function getAvailableFields(ContentTypeInterface $contentType);

    public function getList($exceptId = false);
}
