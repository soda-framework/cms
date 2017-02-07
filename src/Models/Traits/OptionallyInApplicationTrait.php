<?php

namespace Soda\Cms\Models\Traits;

use Soda;
use Illuminate\Database\Eloquent\Builder;

trait OptionallyInApplicationTrait
{
    /**
     * Adds application_id global scope filter to model.
     */
    public static function bootOptionallyInApplicationTrait()
    {
        static::addGlobalScope('in-application', function (Builder $builder) {
            $builder->whereNull('application_id')
                ->orWhere('application_id', '=', '');

            if($application = Soda::getApplication()) {
                $builder = $builder->orWhere('application_id', '=', Soda::getApplication()->id);
            }
        });
    }
}
