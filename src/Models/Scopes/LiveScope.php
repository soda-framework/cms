<?php

namespace Soda\Cms\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Soda\Cms\Models\Status;

class LiveScope implements Scope {

    public function apply(Builder $builder, Model $model) {
        return $builder->where('status_id', '=', Status::LIVE);
    }
}
