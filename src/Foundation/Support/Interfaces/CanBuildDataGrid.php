<?php

namespace Soda\Cms\Foundation\Support\Interfaces;

use Zofe\Rapyd\DataFilter\DataFilter;
use Zofe\Rapyd\DataGrid\DataGrid;

interface CanBuildDataGrid
{
    public function getFilteredGrid($perPage);
    public function buildFilter($model);
    public function buildGrid(DataFilter $filter);
    public function addButtonsToGrid(DataGrid $grid, $editRoute = null, $deleteRoute = null);
    public function getGridView();
}
