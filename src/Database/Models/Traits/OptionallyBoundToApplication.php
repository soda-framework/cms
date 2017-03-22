<?php

namespace Soda\Cms\Database\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait OptionallyBoundToApplication
{
    /**
     * Adds application_id global scope filter to model.
     */
    public static function bootOptionallyBoundToApplication()
    {
        static::addGlobalScope('in-application', function (Builder $builder) {
            $builder->where(function ($sq) {
                $sq->whereNull('application_id')->orWhere('application_id', '=', '');

                if ($application = app('soda')->getApplication()) {
                    $sq->orWhere('application_id', '=', $application->getKey());
                }
            });
        });
    }
}
