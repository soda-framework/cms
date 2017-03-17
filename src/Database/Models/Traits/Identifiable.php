<?php

namespace Soda\Cms\Database\Models\Traits;

trait Identifiable
{
    public function setIdentifierAttribute($value)
    {
        $this->attributes['identifier'] = str_slug($value, '-');

        return $this;
    }
}
