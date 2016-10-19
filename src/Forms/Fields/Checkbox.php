<?php

namespace Soda\Cms\Forms\Fields;

use Soda\Cms\Forms\AbstractFormField;

class Checkbox extends AbstractFormField
{
    protected $view = "checkbox";

    public function getDefaultParameters()
    {
        return [
            'checked-value'   => '',
            'unchecked-value' => '',
        ];
    }
}
