<?php

namespace Soda\Cms\Database\Support\Interfaces;

use Zofe\Rapyd\DataGrid\DataGrid;
use Zofe\Rapyd\DataFilter\DataFilter;

interface CanBuildDataGrid
{
    public function getFilteredGrid($perPage);

    public function buildFilter($model);

    public function buildGrid(DataFilter $filter);

    public function addButtonsToGrid(DataGrid $grid, $editRoute = null, $deleteRoute = null);

    public function getGridView();
}
