<?php

namespace Soda\Cms\Foundation\Forms\Fields;

use Soda\Cms\Foundation\Forms\AbstractFormField;

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
