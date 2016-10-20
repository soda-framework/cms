<?php

namespace Soda\Cms\Database\Support\Repositories\Traits;

use Zofe\Rapyd\DataFilter\DataFilter;
use Zofe\Rapyd\DataGrid\DataGrid;

trait BuildsDataGrids
{
    public function buildFilter($model)
    {
        $filter = (new DataFilter)->source($model);
        $filter->add('name', 'name', 'text');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        return $filter;
    }

    public function buildGrid(DataFilter $filter)
    {
        $grid = (new DataGrid)->source($filter);
        $grid->add('name', 'Name', true);
        $grid->add('description', 'Description', true);
        $grid->orderBy('id', 'desc');

        return $grid;
    }

    public function addButtonsToGrid(DataGrid $grid, $editRoute = null, $deleteRoute = null)
    {
        $width = 0;

        if ($editRoute) $width += 80;
        if ($deleteRoute) $width += 100;

        if ($width > 0) {
            $grid->add('{{ $id }}', 'Options')->style('width:'.$width.'px')->cell(function ($value) use ($editRoute, $deleteRoute) {
                $buttons = '';
                if ($editRoute) {
                    $buttons .= "<a href='".route($editRoute, $value)."' class='btn btn-warning'><i class='fa fa-pencil'></i> <span>Edit</span></a> ";
                }
                if ($deleteRoute) {
                    $buttons .= "<a href='".route($deleteRoute, $value)."' class='btn btn-danger' data-delete-button><i class='fa fa-remove'></i> <span>Delete</span></a> ";
                }

                return $buttons;
            });
        }

        return $grid;
    }

    public function getGridView()
    {
        return soda_cms_view_path('partials.grid');
    }
}
