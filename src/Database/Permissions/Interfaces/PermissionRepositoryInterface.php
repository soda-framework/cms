<?php
namespace Soda\Cms\Database\Permissions\Interfaces;

use Soda\Cms\Database\Support\Interfaces\BaseRepositoryInterface;
use Soda\Cms\Database\Support\Interfaces\CanBuildDataGrid;

interface PermissionRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getRoles();
}
