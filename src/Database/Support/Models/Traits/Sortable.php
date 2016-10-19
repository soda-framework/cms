<?php

namespace Soda\Cms\Database\Support\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{

    /**
     * Adds position to model on creating event.
     */
    public static function bootSortable()
    {
        static::addGlobalScope('position', function (Builder $builder) {
            $builder->orderBy('position');
        });
    }
}
