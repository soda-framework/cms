<?php

namespace Soda\Cms\Components\Forms\Fields;

use Soda\Cms\Components\Forms\AbstractFormField;

class Dropdown extends AbstractFormField {
    public function getDefaultParameters() {
        return [
            'options' => [],
        ];
    }
}
