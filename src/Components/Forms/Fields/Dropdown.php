<?php

namespace Soda\Cms\Components\Forms\Fields;

use Soda\Cms\Components\Forms\AbstractFormField;

class Dropdown extends AbstractFormField {
    public function getDefaultParameters() {
        return [
            'options' => [
            ],
            'default' => 0,
        ];
    }

    public function getFieldValue() {
        $value = parent::getFieldValue();
        if($value) {
            return $value;
        }

        $parameters = $this->parseFieldParameters();
        return $parameters['default'];
    }
}
