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
    protected static $_publishDateField = null;

    /**
     * Automatically filters model to only show live items.
     */
    public static function bootDraftable()
    {
        if (isset(static::$publishDateField)) {
            static::setPublishDateField(static::$publishDateField);
        }

        static::addGlobalScope('published', function (Builder $builder) {
            if (static::isDraftsEnabled()) {
                $builder->where('status', '=', Constants::STATUS_LIVE);

                if (static::publishDateFieldEnabled()) {
                    $builder->where(function ($subQuery) {
                        $subQuery->whereNull(static::$_publishDateField)->orWhere(static::$_publishDateField, '<', static::getConvertedNow());
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

    public static function publishDateFieldEnabled()
    {
        return static::$_publishDateField !== null && static::$_publishDateField !== '';
    }

    public static function setPublishDateField($field = null)
    {
        static::$_publishDateField = $field;
    }

    public function getPublishDate()
    {
        $field = static::publishDateFieldEnabled() ? static::$_publishDateField : 'created_at';

        return Carbon::parse($this->getAttribute($field))->setTimezone(config('soda.cms.publish_timezone', 'UTC'));
    }

    public function isPublished()
    {
        $isLive = $this->status == Constants::STATUS_LIVE;

        if ($isLive && static::publishDateFieldEnabled()) {
            $isLive = static::getConvertedNow() >= $this->published_at;
        }

        return $isLive;
    }

    protected static function getConvertedNow()
    {
        return Carbon::now(config('soda.cms.publish_timezone', 'UTC'))->setTimezone('UTC');
    }

    public function getPublishedAtDiff()
    {
        return $this->getPublishDate()->diffForHumans();
    }
}
