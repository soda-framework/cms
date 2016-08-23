<?php

namespace Soda\Cms\Components;

class Status {
    const DRAFT = 0;
    const LIVE = 1;

    public static function all() {
        return [
            static::DRAFT => 'Draft',
            static::LIVE  => 'Published',
        ];
    }
}
