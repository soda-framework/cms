<?php

namespace Soda\Cms\Database\Repositories\Contracts;

interface UserRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getRoles($filterLevel = true);
}
