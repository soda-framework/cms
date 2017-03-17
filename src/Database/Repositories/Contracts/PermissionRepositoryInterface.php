<?php

namespace Soda\Cms\Database\Repositories\Contracts;

interface PermissionRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getRoles();
}
