<?php

namespace Soda\Cms\Database\Models\Traits;

use Soda\Cms\Database\Models\Media;

trait HasMedia
{
    public function media()
    {
        return $this->hasMany(Media::class, 'related_id')->where('related_table', $this->getTable());
    }

    public function getMedia($field)
    {
        if (! $this->relationLoaded('media')) {
            $this->load('media');
        }

        return $this->getRelation('media')->where('related_field', $field);
    }
}
