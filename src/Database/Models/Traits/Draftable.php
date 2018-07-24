<?php

namespace Soda\Cms\Database\Models\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Soda\Cms\Foundation\Constants;

trait Draftable
{
    protected static $drafts = true;
    protected static $publishDateField = "";

    /**
     * Automatically filters model to only show live items.
     */
    public static function bootDraftable()
    {
        static::addGlobalScope('published', function (Builder $builder) {
            if (static::isDraftsEnabled()) {
                $builder->where('status', '=', Constants::STATUS_LIVE);

                if (static::publishDateFieldEnabled()) {
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
    
    public static function publishDateFieldEnabled()
    {
        return static::$publishDateField !== null && static::$publishDateField !== "";
    }
    
    public static function setPublishDateField($field = null)
    {
        static::$publishDateField = $field;
    }

    public function getPublishDate()
    {
        $field = static::publishDateFieldEnabled() ? static::$publishDateField : 'created_at';

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
