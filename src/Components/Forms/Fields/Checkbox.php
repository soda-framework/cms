<?php

namespace Soda\Cms\Components\Forms\Fields;

use Soda\Cms\Components\Forms\AbstractFormField;

class Checkbox extends AbstractFormField {
    protected $view = "checkbox";

    public function getDefaultParameters() {
        return [
            'checked-value' => '',
            'unchecked-value' => '',
        ];
    }
}
