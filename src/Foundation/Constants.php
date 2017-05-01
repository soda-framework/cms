<?php

namespace Soda\Cms\Foundation;

class Constants
{
    const STATUS_DRAFT = 0;
    const STATUS_LIVE = 1;

    const CONTENT_ACTION_TYPES = [
        'view'       => 'View',
        'controller' => 'Controller',
    ];

    public static function statuses()
    {
        return [
            static::STATUS_DRAFT => 'Draft',
            static::STATUS_LIVE  => 'Published',
        ];
    }
}
