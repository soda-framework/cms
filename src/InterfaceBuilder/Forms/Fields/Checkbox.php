<?php

namespace Soda\Cms\InterfaceBuilder\Forms\Fields;

use Soda\Cms\InterfaceBuilder\Forms\AbstractFormField;

class Checkbox extends AbstractFormField
{
    protected $view = 'checkbox';

    public function getDefaultParameters()
    {
        return [
            'checked-value'   => '',
            'unchecked-value' => '',
        ];
    }
}
