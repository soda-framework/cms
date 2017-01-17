<?php

namespace Soda\Cms\Forms\Fields;

use Soda\Cms\Forms\AbstractFormField;
use Soda\Cms\Forms\Fields\Traits\HasArrayableValue;

class Tags extends AbstractFormField
{
    use HasArrayableValue;

    protected $view = 'tags';

    public function getDefaultParameters()
    {
        return [
            'array-save' => 'json',
        ];
    }
}
