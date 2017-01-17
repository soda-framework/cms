<?php

namespace Soda\Cms\Forms\Fields;

use Soda\Cms\Forms\AbstractFormField;
use Soda\Cms\Forms\Fields\Traits\HasArrayableValue;

class Combobox extends AbstractFormField
{
    use HasArrayableValue;
    protected $view = 'dropdown_advanced';

    public function getDefaultParameters()
    {
        return [
            'multiple'             => false,
            'combo'                => true,
            'array-save'           => 'json',
            'options'              => [],
            'settings'             => [
                'placeholder'             => 'Please select...',
                'minimumResultsForSearch' => 'infinity',
                'theme'                   => 'bootstrap',
            ],
        ];
    }
}
