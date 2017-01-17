<?php

namespace Soda\Cms\Database\Pages\Interfaces;

use Soda\Cms\Database\Support\Interfaces\CanBuildDataGrid;
use Soda\Cms\Database\Support\Interfaces\BaseRepositoryInterface;

interface PageTypeRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getBlockTypes();

    public function getAvailableBlockTypes(PageTypeInterface $pageType);

    public function getList($exceptId = false);
}
