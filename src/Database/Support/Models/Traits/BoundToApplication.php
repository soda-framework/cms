<?php

namespace Soda\Cms\Database\Support\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Soda;

trait BoundToApplication
{
    /**
     * Adds application_id global scope filter to model.
     */
    public static function bootBoundToApplication()
    {
        static::addGlobalScope('in-application', function (Builder $builder) {
            return $builder->where('application_id', '=', app('soda')->getApplication()->getKey());
        });
    }
}
