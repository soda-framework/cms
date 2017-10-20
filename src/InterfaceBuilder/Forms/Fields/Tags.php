<?php

namespace Soda\Cms\InterfaceBuilder\Forms\Fields;

use Soda\Cms\InterfaceBuilder\Forms\AbstractFormField;
use Soda\Cms\InterfaceBuilder\Forms\Fields\Traits\HasArrayableValue;

class Tags extends AbstractFormField
{
    use HasArrayableValue;

    protected $view = 'tags';

    public function getDefaultParameters()
    {
        return [
            'array-save' => 'json',
            'settings'   => [
                'theme' => 'bootstrap',
            ],
        ];
    }
}
