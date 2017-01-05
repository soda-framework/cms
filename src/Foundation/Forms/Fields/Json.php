<?php

namespace Soda\Cms\Foundation\Forms\Fields;

use Soda\Cms\Foundation\Forms\AbstractFormField;

class Json extends AbstractFormField
{
    protected $view = "json";

    public function getFieldValue()
    {
        $value = parent::getFieldValue();

        $json = json_encode($value);

        return ($json && $json != "null" && $json != "[]") ? $json : '{}';
    }
}
