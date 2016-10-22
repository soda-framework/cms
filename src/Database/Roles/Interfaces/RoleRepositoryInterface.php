<?php
namespace Soda\Cms\Database\Roles\Interfaces;

use Soda\Cms\Database\Support\Interfaces\BaseRepositoryInterface;
use Soda\Cms\Database\Support\Interfaces\CanBuildDataGrid;

interface RoleRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getPermissions();
}
