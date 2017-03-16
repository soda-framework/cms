<?php

namespace Soda\Cms\FrontendBuilder\Forms\Fields;

use Soda\Cms\FrontendBuilder\Forms\AbstractFormField;

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
