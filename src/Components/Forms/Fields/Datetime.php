<?php

namespace Soda\Cms\Components\Forms\Fields;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Soda\Cms\Components\Forms\AbstractFormField;

class Datetime extends AbstractFormField {
    protected $view = "soda::inputs.datetime";

    public function getDefaultParameters() {
        return [
            'icons'         => [
                'time'     => "fa fa-clock-o",
                'date'     => "fa fa-calendar",
                'up'       => "fa fa-caret-up",
                'down'     => "fa fa-caret-down",
                'previous' => "fa fa-caret-left",
                'next'     => "fa fa-caret-right",
            ],
            'input-format'  => 'm/d/Y g:i A',
            'output-format' => 'Y-m-d H:i:s',
        ];
    }

    public function getFieldValue() {
        $value = parent::getFieldValue();
        $parameters = $this->parseFieldParameters();

        return $value ? Carbon::createFromFormat($parameters['output-format'], $value)->format($parameters['input-format']) : '';
    }

    public function saveValue(Request $request) {
        $value = parent::saveValue($request);
        $parameters = $this->parseFieldParameters();

        return $value ? Carbon::createFromFormat($parameters['input-format'], $value) : '';
    }
}
