<?php

namespace Soda\Cms\Foundation\Support\Traits;

use Illuminate\Database\Eloquent\Builder;
use Session;
use Soda\Cms\Support\Constants;

trait DraftableTrait
{
    protected static $drafts = true;

    /**
     * Automatically filters model to only show live items
     */
    public static function bootDraftableTrait()
    {
        static::addGlobalScope('published', function (Builder $builder) {
            if (static::isDraftsEnabled()) {
                return $builder->where('status', '=', Constants::STATUS_LIVE);
            }

            return $builder;
        });
    }

    public static function enableDrafts()
    {
        static::$drafts = true;
    }

    public static function disableDrafts()
    {
        static::$drafts = false;
    }

    protected static function isDraftsEnabled()
    {
        return static::$drafts && (Session::get("soda.draft_mode") !== true || !Auth::user()->can('view-drafts'));
    }
}
