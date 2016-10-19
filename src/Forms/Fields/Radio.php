<?php

namespace Soda\Cms\Forms\Fields;

use Soda\Cms\Forms\AbstractFormField;

class Radio extends AbstractFormField
{
    protected $view = "radio";

    public function getDefaultParameters()
    {
        return [
            'options' => [],
        ];
    }
}
