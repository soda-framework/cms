<?php

namespace Soda\Cms\Database\Support\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Rutorika\Sortable\SortableTrait;

trait Sortable
{
    use SortableTrait;
    /**
     * Adds position to model on creating event.
     */
    public static function bootSortable()
    {
        static::addGlobalScope('position', function (Builder $builder) {
            $builder->orderBy(static::getSortableField());
        });

        static::saving(
            function ($model) {
                /* @var Model $model */
                $sortableField = static::getSortableField();
                $query = static::applySortableGroup(static::on(), $model);

                // only automatically calculate next position with max+1 when a position has not been set already
                if ($model->$sortableField === null) {
                    $model->setAttribute($sortableField, $query->max($sortableField) + 1);
                }
            }
        );

        static::deleting(
            function ($model) {
                $model->next()->decrement(static::getSortableField());
            }
        );
    }
}
