<?php

namespace Soda\Cms\Database\Support\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait SortableClosure
{
    /**
     * Adds position to model on creating event.
     */
    public static function bootSortableClosure()
    {
        static::addGlobalScope('position', function (Builder $builder) {
            $builder->orderBy((new static)->getPositionColumn());
        });

        static::saving(function ($model) {
            /* @var Model $model */
            $sortableField = (new static)->getPositionColumn();

            // only automatically calculate next position with max+1 when a position has not been set already
            if ($model->$sortableField === null) {
                $model->setAttribute($sortableField, $model->getNextAfterLastPosition($model->parent_id));
            }
        });

        static::deleting(function ($model) {
            $model->siblings(static::QUERY_NEXT_ALL)->decrement((new static)->getPositionColumn());
        });
    }

    /**
     * Moves $this model after $entity model (and rearrange all entities).
     *
     * @param Model $entity
     *
     * @return \Franzose\ClosureTable\Models\Entity
     * @throws \Exception
     */
    public function moveInto($entity)
    {
        return $this->moveTo($this->getNextAfterLastPosition($entity->id), $entity);
    }

    /**
     * moves $this model after $entity model (and rearrange all entities).
     *
     * @param Model $entity
     *
     * @throws \Exception
     */
    public function moveAfter($entity)
    {
        if ($this->parent_id == $entity->parent_id) {
            $this->position = $entity->position + 1;
            $this->save();
        } else {
            $this->moveTo($entity->position + 1, $entity->parent_id);
        }
    }

    /**
     * moves $this model before $entity model (and rearrange all entities).
     *
     * @param Model $entity
     *
     * @throws SortableException
     */
    public function moveBefore($entity)
    {
        if ($this->parent_id == $entity->parent_id) {
            $this->position = $entity->position - 1;
            $this->save();
        } else {
            $this->moveTo($entity->position - 1, $entity->parent_id);
        }
    }
}
