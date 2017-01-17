<?php

namespace Soda\Cms\Components\Forms\Fields;

use Soda\Cms\Components\Forms\AbstractFormField;

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
