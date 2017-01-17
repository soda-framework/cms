<?php

namespace Soda\Cms\Foundation\Forms\Fields;

use Soda\Cms\Foundation\Forms\AbstractFormField;

class Radio extends AbstractFormField
{
    protected $view = 'radio';

    public function getDefaultParameters()
    {
        return [
            'options' => [],
        ];
    }
}
