<?php

namespace Soda\Cms\Database\Permissions\Interfaces;

use Soda\Cms\Database\Support\Interfaces\CanBuildDataGrid;
use Soda\Cms\Database\Support\Interfaces\BaseRepositoryInterface;

interface PermissionRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getRoles();
}
