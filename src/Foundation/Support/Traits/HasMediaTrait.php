<?php

namespace Soda\Cms\Foundation\Support\Traits;

use Soda\Cms\Foundation\Fields\Interfaces\MediaInterface;

trait HasMediaTrait
{
    public function media()
    {
        return $this->hasMany(resolve_class(MediaInterface::class), 'related_id')->where('related_table', $this->getTable());
    }

    public function getMedia($field)
    {
        if (!$this->media) {
            $this->load('media');
        }

        return $this->media->where('related_field', $field);
    }
}
