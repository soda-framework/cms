<?php

namespace Soda\Cms\Database\Fields\Repositories;

use Zofe\Rapyd\DataGrid\DataGrid;
use Soda\Cms\Support\Facades\Form;
use Zofe\Rapyd\DataFilter\DataFilter;
use Soda\Cms\Database\Fields\Interfaces\FieldInterface;
use Soda\Cms\Database\Support\Repositories\AbstractRepository;
use Soda\Cms\Database\Fields\Interfaces\FieldRepositoryInterface;
use Soda\Cms\Database\Support\Repositories\Traits\BuildsDataGrids;

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
        $grid->add('description', 'Description', true);
        $grid->add('field_type', 'type', true)->cell(function ($value) {
            return ucfirst(str_replace('_', ' ', $value));
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
