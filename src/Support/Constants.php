<?php

namespace Soda\Cms\Support;

class Constants
{
    const STATUS_DRAFT = 0;
    const STATUS_LIVE = 1;

    public static function statuses()
    {
        return [
            static::STATUS_DRAFT => 'Draft',
            static::STATUS_LIVE  => 'Published',
        ];
    }
}
