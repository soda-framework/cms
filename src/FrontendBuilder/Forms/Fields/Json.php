<?php

namespace Soda\Cms\FrontendBuilder\Forms\Fields;

use Soda\Cms\FrontendBuilder\Forms\AbstractFormField;

class Json extends AbstractFormField
{
    protected $view = 'json';

    public function getFieldValue()
    {
        $value = parent::getFieldValue();

        $json = json_encode($value);

        return ($json && $json != 'null' && $json != '[]') ? $json : '{}';
    }
}
