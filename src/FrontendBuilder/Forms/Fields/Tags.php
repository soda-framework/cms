<?php

namespace Soda\Cms\FrontendBuilder\Forms\Fields;

use Soda\Cms\FrontendBuilder\Forms\AbstractFormField;
use Soda\Cms\FrontendBuilder\Forms\Fields\Traits\HasArrayableValue;

class Tags extends AbstractFormField
{
    use HasArrayableValue;

    protected $view = 'tags';

    public function getDefaultParameters()
    {
        return [
            'array-save'           => 'json',
            'settings'             => [
                'theme'                   => 'bootstrap',
            ],
        ];
    }
}
