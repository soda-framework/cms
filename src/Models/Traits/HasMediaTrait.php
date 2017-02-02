<?php

namespace Soda\Cms\Models\Traits;

use Soda\Cms\Models\Media;

trait HasMediaTrait
{
    public function media()
    {
        return $this->hasMany(Media::class, 'related_id')->where('related_table', $this->getTable());
    }

    public function getMedia($field)
    {
        if (! $this->media) {
            $this->load('media');
        }

        return $this->media->where('related_field', $field);
    }
}
