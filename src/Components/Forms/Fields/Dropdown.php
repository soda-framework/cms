<?php

namespace Soda\Cms\Components\Forms\Fields;

use Soda\Cms\Components\Forms\AbstractFormField;

class Dropdown extends AbstractFormField
{
    protected $view = "dropdown";

    public function getDefaultParameters()
    {
        return [
            'options' => [],
        ];
    }
}
