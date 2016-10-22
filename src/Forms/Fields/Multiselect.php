<?php

namespace Soda\Cms\Forms\Fields;

use Soda\Cms\Forms\AbstractFormField;
use Soda\Cms\Forms\Fields\Traits\HasArrayableValue;

class Multiselect extends AbstractFormField
{
    use HasArrayableValue;

    protected $view = "multiselect";

    public function getDefaultParameters()
    {
        return [
            'array-save'           => 'json',
            'style'                => 'btn-dropdown',
            'selected-text-format' => 'count > 3',
            'placeholder'          => 'Please select...',
            'options'              => [],
        ];
    }
}
