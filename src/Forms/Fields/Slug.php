<?php

namespace Soda\Cms\Forms\Fields;

class Slug extends Text
{
    protected $view = 'slug';

    public function getDefaultParameters()
    {
        return [
            'allow-external' => true,
            'from'           => '#field_name',
            'prefix'         => '',
        ];
    }
}
