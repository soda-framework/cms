<?php

namespace Soda\Cms\Models\Traits;

use Session;
use Soda\Cms\Components\Status;
use Illuminate\Database\Eloquent\Builder;

trait DraftableTrait
{
    protected static $drafts = true;

    /**
     * Automatically filters model to only show live items.
     */
    public static function bootDraftableTrait()
    {
        if (static::isDraftsEnabled()) {
            static::addGlobalScope('published', function (Builder $builder) {
                return $builder->where('status', '=', Status::LIVE);
            });
        }
    }

    protected static function isDraftsEnabled()
    {
        return static::$drafts && Session::get('soda.draft_mode') !== true;
    }

    public static function enableDrafts()
    {
        static::$drafts = true;
    }

    public static function disableDrafts()
    {
        static::$drafts = false;
    }
}
