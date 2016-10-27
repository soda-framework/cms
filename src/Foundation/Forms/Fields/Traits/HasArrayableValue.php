<?php

namespace Soda\Cms\Foundation\Forms\Fields\Traits;

use Illuminate\Http\Request;

trait HasArrayableValue
{
    /**
     * Manipulate the field input before returning the value that should be saved
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|string
     */
    public function getSaveValue(Request $request)
    {
        $value = parent::getSaveValue($request);

        if ($this->isMulti() && $this->isJsonable()) {
            return json_encode($value);
        } elseif ($this->isMulti() && $this->isDelimited()) {
            $delimiter = $this->getDelimiter($value, 8);

            return implode($delimiter, $value);
        }

        return $value;
    }

    public function getFieldValue()
    {
        $value = parent::getFieldValue();

        if ($this->isMulti() && $this->isJsonable()) {
            return json_decode($value);
        } elseif ($this->isMulti() && $this->isDelimited()) {
            $delimiter = $this->getDelimiter($value, 8);

            return explode($delimiter, $value);
        }

        return $value;
    }

    protected function isMulti()
    {
        $parameters = $this->parseFieldParameters();

        return isset($parameters['multiple']) && $parameters['multiple'];
    }

    protected function isJsonable()
    {
        $parameters = $this->parseFieldParameters();

        return isset($parameters['array-save']) && $parameters['array-save'] === 'json';
    }

    protected function isDelimited()
    {
        $parameters = $this->parseFieldParameters();

        return isset($parameters['array-save']) && starts_with($parameters['array-save'], 'delimit:');
    }

    protected function getDelimiter($value)
    {
        return substr($value, 8);
    }
}
