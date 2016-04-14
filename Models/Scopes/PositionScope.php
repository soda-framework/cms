<?php
/**
 * Created by PhpStorm.
 * User: sidavies
 * Date: 7/03/2016
 * Time: 12:27 PM
 */

namespace Soda\Models\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;

class PositionScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        return $builder->orderBy('position');
    }
}