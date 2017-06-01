<?php

namespace Soda\Cms\Database\Models\Traits;

use Carbon\Carbon;
use Soda\Cms\Foundation\Constants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;

trait Draftable
{
    protected static $drafts = true;

    protected static function getConvertedNow() {
        return Carbon::now(config('soda.cms.publish_timezone', 'UTC'))->setTimezone('UTC');
    }

    public function isPublished()
    {
        $isLive = $this->status == Constants::STATUS_LIVE;

        if($isLive && isset(static::$publishDateField)) {
            $isLive = static::getConvertedNow() >= $this->published_at;
        }

        return $isLive;
    }

    /**
     * Automatically filters model to only show live items.
     */
    public static function bootDraftable()
    {
        static::addGlobalScope('published', function (Builder $builder) {
            if (static::isDraftsEnabled()) {
                $builder->where('status', '=', Constants::STATUS_LIVE);

                if (isset(static::$publishDateField)) {
                    $builder->where(function ($subQuery) {
                        $subQuery->whereNull(static::$publishDateField)->orWhere(static::$publishDateField, '<', static::getConvertedNow());
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
        if (static::$drafts && Session::get('soda.draft_mode') == true) {
            $sodaUser = Auth::guard('soda')->user();
            if ($sodaUser && $sodaUser->can('view-drafts')) {
                return false;
            }
        }

        return static::$drafts;
    }
}
