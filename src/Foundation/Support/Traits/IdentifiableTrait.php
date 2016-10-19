<?php

namespace Soda\Cms\Foundation\Support\Traits;

trait IdentifiableTrait
{
    public function setIdentifierAttribute($value)
    {
        $this->attributes['identifier'] = str_slug($value, '-');
    }
}
