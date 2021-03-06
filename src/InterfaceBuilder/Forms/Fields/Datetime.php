<?php

namespace Soda\Cms\InterfaceBuilder\Forms\Fields;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Soda\Cms\InterfaceBuilder\Forms\AbstractFormField;

class Datetime extends AbstractFormField
{
    protected $view = 'datetime';

    public function getDefaultParameters()
    {
        return [
            'options'       => [
                'format' => 'DD/MM/Y h:mm A',
                'icons'  => [
                    'time'     => 'fa fa-clock-o',
                    'date'     => 'fa fa-calendar',
                    'up'       => 'fa fa-caret-up',
                    'down'     => 'fa fa-caret-down',
                    'previous' => 'fa fa-caret-left',
                    'next'     => 'fa fa-caret-right',
                ],
            ],
            'input-format'  => 'd/m/Y g:i A',
            'output-format' => 'Y-m-d H:i:s',
        ];
    }

    public function getFieldValue()
    {
        $value = parent::getFieldValue();
        $parameters = $this->parseFieldParameters();

        if (! $value) {
            return '';
        }

        $dateTime = $value instanceof Carbon ? $value : Carbon::createFromFormat($parameters['output-format'], $value);

        if (isset($parameters['timezone']) && $parameters['timezone']) {
            $dateTime->setTimezone($parameters['timezone']);
        }

        return $dateTime->format($parameters['input-format']);
    }

    public function getSaveValue(Request $request)
    {
        $value = parent::getSaveValue($request);
        $parameters = $this->parseFieldParameters();

        if (! $value) {
            return;
        }

        $tz = isset($parameters['timezone']) && $parameters['timezone'] ? $parameters['timezone'] : null;

        return Carbon::createFromFormat($parameters['input-format'], $value, $tz)->setTimezone('UTC');
    }
}
