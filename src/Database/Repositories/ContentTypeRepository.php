<?php

namespace Soda\Cms\Database\Repositories;

use Illuminate\Http\Request;
use Soda\Cms\Database\Models\Contracts\ContentTypeInterface;
use Soda\Cms\Database\Repositories\Traits\BuildsDataGrids;
use Soda\Cms\Database\Repositories\Contracts\ContentTypeRepositoryInterface;

class ContentTypeRepository extends AbstractRepository implements ContentTypeRepositoryInterface
{
    use BuildsDataGrids;
    protected $model;

    public function __construct(ContentTypeInterface $model)
    {
        $this->model = $model;
    }

    public function getBlockTypes()
    {
        return app('soda.block-type.repository')->getAll();
    }

    public function getFields()
    {
        return app('soda.field.repository')->getAll();
    }

    public function getAvailableBlockTypes(ContentTypeInterface $contentType)
    {
        if (! $contentType->relationLoaded('blockTypes')) {
            $contentType->load('blockTypes');
        }

        return $this->getBlockTypes()->diff($contentType->getRelation('blockTypes'));
    }

    public function getAvailableFields(ContentTypeInterface $contentType)
    {
        if (! $contentType->relationLoaded('fields')) {
            $contentType->load('fields');
        }

        return $this->getFields()->diff($contentType->getRelation('fields'));
    }

    public function save(Request $request, $id = null)
    {
        $model = $id ? $this->model->findOrFail($id) : $this->newInstance();
        $model->fill($request->all());

        if ($model->id && $model->isDirty('is_folder')) {
            $model->content()->update(['is_folder' => $this->model->is_folder]);
        }

        if ($model->id && $model->isDirty('is_sluggable')) {
            $model->content()->update(['is_sluggable' => $this->model->is_sluggable]);
        }

        $model->save();

        $allPageTypes = $request->input('page_types') ? array_keys($request->input('page_types')) : [];
        $restrictedPageTypes = array_keys(array_filter((array) $request->input('page_types')));
        $isAllAllowed = $request->input('page_types_restricted') == '0' || $allPageTypes == $restrictedPageTypes;

        $model->pageTypes()->sync($isAllAllowed ? [] : $restrictedPageTypes);

        return $model;
    }

    public function getFilteredGrid($perPage)
    {
        $filter = $this->buildFilter($this->model);
        $grid = $this->buildGrid($filter);
        $grid = $this->addButtonsToGrid($grid, 'soda.content-types.edit', 'soda.content-types.destroy');
        $grid->paginate($perPage)->getGrid($this->getGridView());

        return compact('filter', 'grid');
    }

    public function getList($exceptId = false)
    {
        $contentTypes = $this->model;

        if ($exceptId !== false) {
            $contentTypes = $contentTypes->where('id', '!=', $exceptId);
        }

        return $contentTypes->get();
    }
}
