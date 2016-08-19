<?php

namespace Soda\Cms\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PositionScope implements Scope {
    public function apply(Builder $builder, Model $model) {
        return $builder->orderBy('position');
    }
}
