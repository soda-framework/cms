<?php

namespace Soda\Cms\Database\Support\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Soda\Cms\Support\Constants;
use Soda\Cms\Support\Facades\Soda;

trait Draftable
{
    protected static $drafts = true;

    /**
     * Automatically filters model to only show live items
     */
    public static function bootDraftable()
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
        return static::$drafts && (Session::get("soda.draft_mode") !== true || !Soda::auth()->user()->can('view-drafts'));
    }
}
