<?php

namespace Soda\Cms\Database\Repositories\Traits;

use Zofe\Rapyd\DataGrid\DataGrid;
use Zofe\Rapyd\DataFilter\DataFilter;

trait BuildsDataGrids
{
    public function buildFilter($model)
    {
        $filter = (new DataFilter)->source($model);
        $filter->add('name', 'Name', 'text');
        $filter->submit('Search');
        $filter->reset('Clear');
        $filter->build();

        return $filter;
    }

    public function buildGrid(DataFilter $filter)
    {
        $grid = (new DataGrid)->source($filter);
        $grid->add('name', 'Name', true);
        $grid->add('description', 'Description')->attributes(['class' => 'hidden-sm hidden-xs']);

        $grid->row(function ($row) {
            $row->cell('description')->attributes(['class' => 'hidden-sm hidden-xs']);
        });

        $grid->orderBy('id', 'asc');
        $grid->attr('class', 'table table-striped middle');

        return $grid;
    }

    public function addButtonsToGrid(DataGrid $grid, $editRoute = null, $deleteRoute = null)
    {
        $width = 40;

        if ($editRoute) {
            $width += 83;
        }
        if ($deleteRoute) {
            $width += 103;
        }

        if ($width > 40) {
            $grid->add('{{ $id }}', 'Options')->style('width:'.$width.'px')->cell(function ($value) use ($editRoute, $deleteRoute) {
                $buttons = '';
                if ($editRoute) {
                    $buttons .= "<a href='".route($editRoute, $value)."' class='btn btn-warning'><span>Edit</span></a> ";
                }
                if ($deleteRoute) {
                    $buttons .= "<a href='".route($deleteRoute, $value)."' class='btn btn-danger' data-delete-button><span>Delete</span></a> ";
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
