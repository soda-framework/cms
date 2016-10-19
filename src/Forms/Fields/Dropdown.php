<?php

namespace Soda\Cms\Forms\Fields;

use Soda\Cms\Forms\AbstractFormField;

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
