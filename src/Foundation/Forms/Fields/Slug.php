<?php

namespace Soda\Cms\Foundation\Forms\Fields;

class Slug extends Text
{
    protected $view = "slug";

    public function getDefaultParameters()
    {
        return [
            'from'   => '#field_name',
            'prefix' => '',
        ];
    }
}
