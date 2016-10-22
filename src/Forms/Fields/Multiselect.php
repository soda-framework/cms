<?php

namespace Soda\Cms\Forms\Fields;

use Soda\Cms\Forms\AbstractFormField;

class Multiselect extends AbstractFormField
{
    protected $view = "multiselect";

    public function getDefaultParameters()
    {
        return [
            'style'                => 'btn-dropdown',
            'selected-text-format' => 'count > 3',
            'placeholder'          => 'Please select...',
            'options'              => [],
        ];
    }
}
