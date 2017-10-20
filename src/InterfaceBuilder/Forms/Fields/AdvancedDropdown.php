<?php

namespace Soda\Cms\InterfaceBuilder\Forms\Fields;

use Soda\Cms\InterfaceBuilder\Forms\AbstractFormField;

class AdvancedDropdown extends AbstractFormField
{
    protected $view = 'dropdown_advanced';

    public function getDefaultParameters()
    {
        return [
            'multiple' => false,
            'combo'    => false,
            'options'  => [],
            'settings' => [
                'placeholder'             => 'Please select...',
                'minimumResultsForSearch' => 'infinity',
                'theme'                   => 'bootstrap',
            ],
        ];
    }
}
