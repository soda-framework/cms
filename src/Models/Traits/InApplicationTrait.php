<?php

namespace Soda\Cms\Models\Traits;

use Soda;
use Illuminate\Database\Eloquent\Builder;

trait InApplicationTrait
{
    /**
     * Adds application_id global scope filter to model.
     */
    public static function bootInApplicationTrait()
    {
        static::addGlobalScope('in-application', function (Builder $builder) {
            return $builder->where('application_id', '=', Soda::getApplication()->id);
        });
    }
}
