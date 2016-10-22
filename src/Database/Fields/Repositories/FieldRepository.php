<?php
namespace Soda\Cms\Database\Fields\Repositories;

use Soda\Cms\Database\Fields\Interfaces\FieldInterface;
use Soda\Cms\Database\Fields\Interfaces\FieldRepositoryInterface;
use Soda\Cms\Database\Support\Repositories\AbstractRepository;
use Soda\Cms\Database\Support\Repositories\Traits\BuildsDataGrids;

class FieldRepository extends AbstractRepository implements FieldRepositoryInterface
{
    use BuildsDataGrids;

    protected $model;

    public function __construct(FieldInterface $model)
    {
        $this->model = $model;
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
