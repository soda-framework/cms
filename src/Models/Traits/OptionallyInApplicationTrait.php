<?php

namespace Soda\Cms\Models\Traits;

use Soda;
use Illuminate\Database\Eloquent\Builder;

trait OptionallyInApplicationTrait
{
    /**
     * Adds application_id global scope filter to model.
     */
    public static function bootOptinallyInApplication()
    {
        static::addGlobalScope('in-application', function (Builder $builder) {
            $builder->where('application_id', '=', Soda::getApplication()->id)
                    ->orWhereNull('application_id')
                    ->orWhere('application_id', '=', '');
        });
    }
}
