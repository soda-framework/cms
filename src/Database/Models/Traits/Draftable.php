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
    public static $publishDateField = null;

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

    public static function enableDrafts()
    {
        static::$drafts = true;
    }

    public static function disableDrafts()
    {
        static::$drafts = false;
    }

    public function getPublishDate()
    {
        $field = isset(static::$publishDateField) ? static::$publishDateField : 'created_at';

        return Carbon::parse($this->$field)->setTimezone(config('soda.cms.publish_timezone', 'UTC'));
    }

    public function isPublished()
    {
        $isLive = $this->status == Constants::STATUS_LIVE;

        if ($isLive && isset(static::$publishDateField)) {
            $isLive = static::getConvertedNow() >= $this->published_at;
        }

        return $isLive;
    }

    protected static function getConvertedNow()
    {
        return Carbon::now(config('soda.cms.publish_timezone', 'UTC'))->setTimezone('UTC');
    }

//    public function getPublishedAtAttribute($date) {
//        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('F j, Y @ g:i A');
//    }

    public function getPublishedAt($date) {
        dd($date);
        return Carbon::parse($date)->diffForHumans();
    }
}
