<?php

namespace Soda\Cms\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait PositionableTrait
{
    /**
     * Adds position to model on creating event.
     */
    public static function bootPositionableTrait()
    {
        static::addGlobalScope('position', function (Builder $builder) {
            $builder->orderBy('position');
        });
    }
}
