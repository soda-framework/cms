<?php

namespace Soda\Cms\Database\Fields\Interfaces;

use Soda\Cms\Database\Support\Interfaces\CanBuildDataGrid;
use Soda\Cms\Database\Support\Interfaces\BaseRepositoryInterface;

interface FieldRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getFieldTypes();
}
