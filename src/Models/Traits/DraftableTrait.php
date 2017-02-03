<?php

namespace Soda\Cms\Models\Traits;

use Carbon\Carbon;
use Soda\Cms\Support\Constants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;

trait DraftableTrait
{
    protected static $drafts = true;

    /**
     * Automatically filters model to only show live items.
     */
    public static function bootDraftableTrait()
    {
        static::addGlobalScope('published', function (Builder $builder) {
            if (static::isDraftsEnabled()) {
                $builder->where('status', '=', Constants::STATUS_LIVE);

                if (isset(static::$publishDateField)) {
                    $builder->where(function ($subQuery) {
                        $subQuery->where(static::$publishDateField, '<', Carbon::now())->orWhereNull(static::$publishDateField);
                    });
                }
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
        return static::$drafts && (Session::get('soda.draft_mode') !== true || ! Auth::user()->can('view-drafts'));
    }
}
