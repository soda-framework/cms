<?php

namespace Soda\Cms\Foundation\Forms\Fields;

use Soda\Cms\Foundation\Forms\AbstractFormField;

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
