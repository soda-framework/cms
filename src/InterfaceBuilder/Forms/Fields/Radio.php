<?php

namespace Soda\Cms\InterfaceBuilder\Forms\Fields;

use Soda\Cms\InterfaceBuilder\Forms\AbstractFormField;

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
