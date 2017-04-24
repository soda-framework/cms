<?php

namespace Soda\Cms\InterfaceBuilder\Forms\Fields;

use Soda\Cms\InterfaceBuilder\Forms\AbstractFormField;

class Dropdown extends AbstractFormField
{
    protected $view = 'dropdown';

    public function getDefaultParameters()
    {
        return [
            'options' => [],
        ];
    }
}
