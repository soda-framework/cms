<?php

namespace Soda\Cms\Database\Support\Models\Traits;

trait HasMedia
{
    public function media()
    {
        return $this->hasMany(resolve_class('soda.media.model'), 'related_id')->where('related_table', $this->getTable());
    }

    public function getMedia($field)
    {
        if (!$this->media) {
            $this->load('media');
        }

        return $this->media->where('related_field', $field);
    }
}
