<?php

namespace Soda\Cms\Database\Models\Traits;

trait Identifiable
{
    public function setIdentifierAttribute($value)
    {
        $identifier = $this->formatIdentifier($value);

        $this->attributes['identifier'] = $identifier;

        return $identifier;
    }

    public function formatIdentifier($value)
    {
        return str_slug($value, '-');
    }

    public function getIdentifierAttribute($value)
    {
        return $this->formatIdentifier($value);
    }
}
