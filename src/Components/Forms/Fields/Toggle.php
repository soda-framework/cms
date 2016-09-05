<?php

namespace Soda\Cms\Components\Forms\Fields;

use Illuminate\Http\Request;
use Soda\Cms\Components\Forms\AbstractFormField;

class Toggle extends AbstractFormField {
    protected $view = "toggle";

    public function getDefaultParameters() {
        return [
            'on-value'  => '1',
            'off-value' => '0',
        ];
    }

    public function getSaveValue(Request $request) {
        $value = parent::getSaveValue($request);

        if($value == null) {
            $parameters = $this->parseFieldParameters();
            $value == $parameters['off-value'];
        }

        return $value;
    }
}
