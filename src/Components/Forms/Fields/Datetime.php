<?php

namespace Soda\Cms\Components\Forms\Fields;

use Carbon\Carbon;
use Soda\Cms\Components\Forms\AbstractFormField;

class Datetime extends AbstractFormField {
    public function getDefaultParameters() {
        return [
            'icons' => [
            'time'         => "fa fa-clock-o",
				'date'     => "fa fa-calendar",
				'up'       => "fa fa-caret-up",
				'down'     => "fa fa-caret-down",
				'previous' => "fa fa-caret-left",
				'next'     => "fa fa-caret-right",
			]
        ];
    }

    public function getFieldValue() {
        $value = parent::getFieldValue();

        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('m/d/Y g:i A') : '';
    }
}
