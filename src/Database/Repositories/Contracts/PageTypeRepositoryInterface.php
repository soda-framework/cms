<?php

namespace Soda\Cms\Database\Repositories\Contracts;

use Soda\Cms\Database\Models\Contracts\PageTypeInterface;

interface PageTypeRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getBlockTypes();

    public function getAvailableBlockTypes(PageTypeInterface $pageType);

    public function getFields();

    public function getAvailableFields(PageTypeInterface $pageType);

    public function getList($exceptId = false);
}
