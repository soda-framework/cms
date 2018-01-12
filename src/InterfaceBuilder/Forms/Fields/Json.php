<?php

namespace Soda\Cms\InterfaceBuilder\Forms\Fields;

use Soda\Cms\InterfaceBuilder\Forms\AbstractFormField;

class Json extends AbstractFormField
{
    protected $view = 'json';

    public function getFieldValue()
    {
        $value = parent::getFieldValue();

        if (is_array($value)) {
            $value = json_encode($value);
        }

        return ($value && $value != 'null' && $value != '[]') ? $value : '{}';
    }
}
