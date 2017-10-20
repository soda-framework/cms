<?php

namespace Soda\Cms\InterfaceBuilder\Forms\Fields\Traits;

use Illuminate\Http\Request;

trait HasArrayableValue
{
    /**
     * Manipulate the field input before returning the value that should be saved.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|string
     */
    public function getSaveValue(Request $request)
    {
        $value = parent::getSaveValue($request);

        if ($this->isJsonable()) {
            return json_encode($value);
        } elseif ($this->isDelimited()) {
            $delimiter = $this->getDelimiter();

            return implode($delimiter, $value);
        } elseif ($this->isArrayable()) {
            return (array) $value;
        }

        return $value;
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

    protected function getDelimiter()
    {
        $parameters = $this->parseFieldParameters();

        return substr($parameters['array-save'], 8);
    }

    protected function isArrayable()
    {
        $parameters = $this->parseFieldParameters();

        return isset($parameters['array-save']) && $parameters['array-save'] === 'array';
    }

    public function renderForTable()
    {
        $fieldValue = $this->getFieldValue();

        if (is_array($fieldValue)) {
            $fieldValue = implode(',', $fieldValue);
        }

        return truncate_words(strip_tags($fieldValue), 10);
    }

    public function getFieldValue()
    {
        $value = parent::getFieldValue();

        if (is_array($value)) {
            return $value;
        }

        if ($this->isJsonable()) {
            return json_decode($value);
        } elseif ($this->isDelimited()) {
            $delimiter = $this->getDelimiter($value, 8);

            return explode($delimiter, $value);
        } elseif ($this->isArrayable()) {
            return (array) $value;
        }

        return $value;
    }
}
