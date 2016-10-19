<?php

namespace Soda\Cms\Database\Support\Models\Traits;

trait Identifiable
{
    public function setIdentifiable($value)
    {
        $this->attributes['identifier'] = str_slug($value, '-');
    }
}
