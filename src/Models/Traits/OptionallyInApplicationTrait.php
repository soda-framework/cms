<?php

namespace Soda\Cms\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Soda;

trait OptionallyInApplicationTrait
{
    /**
     * Adds application_id global scope filter to model.
     */
    public static function bootOptionallyInApplicationTrait()
    {
        static::addGlobalScope('in-application', function (Builder $builder) {
            $builder->where('application_id', '=', Soda::getApplication()->id)
                ->orWhereNull('application_id')
                ->orWhere('application_id', '=', '');
        });
    }
}
