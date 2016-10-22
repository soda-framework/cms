<?php
namespace Soda\Cms\Database\Users\Interfaces;

use Soda\Cms\Database\Support\Interfaces\BaseRepositoryInterface;
use Soda\Cms\Database\Support\Interfaces\CanBuildDataGrid;

interface UserRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getRoles();
}
