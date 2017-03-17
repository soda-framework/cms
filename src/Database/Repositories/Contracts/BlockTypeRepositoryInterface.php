<?php

namespace Soda\Cms\Database\Repositories\Contracts;

use Soda\Cms\Database\Models\Contracts\BlockTypeInterface;

interface BlockTypeRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getFields();

    public function getAvailableFields(BlockTypeInterface $pageType);
}
