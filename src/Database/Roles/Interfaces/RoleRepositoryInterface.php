<?php

namespace Soda\Cms\Database\Roles\Interfaces;

use Soda\Cms\Database\Support\Interfaces\CanBuildDataGrid;
use Soda\Cms\Database\Support\Interfaces\BaseRepositoryInterface;

interface RoleRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getPermissions();
}
