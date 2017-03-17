<?php

namespace Soda\Cms\Database\Repositories;

use Zofe\Rapyd\DataGrid\DataGrid;
use Soda\Cms\Support\Facades\Form;
use Zofe\Rapyd\DataFilter\DataFilter;
use Soda\Cms\Database\Models\Contracts\FieldInterface;
use Soda\Cms\Database\Repositories\Traits\BuildsDataGrids;
use Soda\Cms\Database\Repositories\Contracts\FieldRepositoryInterface;

class FieldRepository extends AbstractRepository implements FieldRepositoryInterface
{
    use BuildsDataGrids;

    protected $model;

    public function __construct(FieldInterface $model)
    {
        $this->model = $model;
    }

    public function getFieldTypes()
    {
        return Form::getFieldTypes();
    }

    /**
     * @param FieldInterface $model
     */
    public function buildFilter($model)
    {
        $filter = (new DataFilter)->source($model);
        $filter->add('name', 'Name', 'text');
        $filter->add('field_type', 'Type', 'select')
            ->option('', '')
            ->options($this->getFieldTypes());
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
        $grid->add('field_type', 'Type', true)->cell(function ($value) {
            return ucfirst(str_replace('_', ' ', $value));
        });

        $grid->row(function ($row) {
            $row->cell('description')->attributes(['class' => 'hidden-sm hidden-xs']);
        });

        $grid->orderBy('id', 'asc');

        return $grid;
    }

    public function getFilteredGrid($perPage)
    {
        $filter = $this->buildFilter($this->model);
        $grid = $this->buildGrid($filter);
        $grid = $this->addButtonsToGrid($grid, 'soda.fields.edit', 'soda.fields.destroy');
        $grid->paginate($perPage)->getGrid($this->getGridView());

        return compact('filter', 'grid');
    }
}
