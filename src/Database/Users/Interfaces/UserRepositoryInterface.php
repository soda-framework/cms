<?php

namespace Soda\Cms\Database\Users\Interfaces;

use Soda\Cms\Database\Support\Interfaces\CanBuildDataGrid;
use Soda\Cms\Database\Support\Interfaces\BaseRepositoryInterface;

interface UserRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getRoles();
}
