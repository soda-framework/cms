<?php

namespace Soda\Cms\Database\Repositories\Contracts;

interface FieldRepositoryInterface extends CanBuildDataGrid, BaseRepositoryInterface
{
    public function getFieldTypes();
}
