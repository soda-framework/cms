<?php

namespace Soda\Cms\Database\Repositories\Contracts;

interface RoleRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getPermissions();
}
