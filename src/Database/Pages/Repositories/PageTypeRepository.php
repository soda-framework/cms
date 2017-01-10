<?php
namespace Soda\Cms\Database\Pages\Repositories;

use Illuminate\Http\Request;
use Soda\Cms\Database\Pages\Interfaces\PageTypeInterface;
use Soda\Cms\Database\Pages\Interfaces\PageTypeRepositoryInterface;
use Soda\Cms\Database\Support\Repositories\AbstractRepository;
use Soda\Cms\Database\Support\Repositories\Traits\BuildsDataGrids;

class PageTypeRepository extends AbstractRepository implements PageTypeRepositoryInterface
{
    use BuildsDataGrids;
    protected $model;

    public function __construct(PageTypeInterface $model)
    {
        $this->model = $model;
    }

    public function save(Request $request, $id = null)
    {
        if($this->model->id && $this->model->isDirty('allowed_children'))
        {
            $this->model->pages()->update(['allowed_children' => $this->model->allowed_children]);
        }

        return parent::save();
    }

    public function getFilteredGrid($perPage)
    {
        $filter = $this->buildFilter($this->model);
        $grid = $this->buildGrid($filter);
        $grid = $this->addButtonsToGrid($grid, 'soda.page-types.edit', 'soda.page-types.destroy');
        $grid->paginate($perPage)->getGrid($this->getGridView());

        return compact('filter', 'grid');
    }
}
