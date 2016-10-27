<?php

namespace Soda\Cms\Foundation\Forms\Fields;

use Soda\Cms\Foundation\Forms\AbstractFormField;
use Soda\Cms\Foundation\Forms\Fields\Traits\HasArrayableValue;
class Multiselect extends AbstractFormField
{
    use HasArrayableValue;
    protected $view = "dropdown_advanced";
    public function getDefaultParameters()
    {
        return [
            'multiple'             => true,
            'array-save'           => 'json',
            'style'                => 'btn-dropdown',
            'selected-text-format' => 'count > 3',
            'placeholder'          => 'Please select...',
            'options'              => [],
        ];
    }
}
