<?php

namespace Soda\Cms\FrontendBuilder\Forms\Fields;

use Soda\Cms\FrontendBuilder\Forms\AbstractFormField;
use Soda\Cms\FrontendBuilder\Forms\Fields\Traits\HasArrayableValue;

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