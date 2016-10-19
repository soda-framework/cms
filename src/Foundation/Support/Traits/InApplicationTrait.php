<?php

namespace Soda\Cms\Foundation\Support\Traits;

use Illuminate\Database\Eloquent\Builder;
use Soda;

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
