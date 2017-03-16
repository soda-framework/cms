<?php

namespace Soda\Cms\FrontendBuilder\Forms\Fields;

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
