<?php

namespace Soda\Cms\InterfaceBuilder\Forms\Fields;

use Soda\Cms\InterfaceBuilder\Forms\AbstractFormField;
use Soda\Cms\InterfaceBuilder\Forms\Fields\Traits\HasArrayableValue;

class Combobox extends AbstractFormField
{
    use HasArrayableValue;
    protected $view = 'dropdown_advanced';

    public function getDefaultParameters()
    {
        return [
            'multiple'   => false,
            'combo'      => true,
            'array-save' => 'json',
            'options'    => [],
            'settings'   => [
                'placeholder'             => 'Please select...',
                'minimumResultsForSearch' => 'infinity',
                'theme'                   => 'bootstrap',
            ],
        ];
    }
}
