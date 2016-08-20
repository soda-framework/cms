<?php

namespace Soda\Cms\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Session;
use Soda\Cms\Components\Status;

trait DraftableTrait {
    /**
     * Automatically filters model to only show live items
     */
    public static function bootDraftableTrait() {
        if(Session::get("soda.draft_mode") !== true) {
            static::addGlobalScope('published', function(Builder $builder){
                return $builder->where('status', '=', Status::LIVE);
            });
        }
    }
}
