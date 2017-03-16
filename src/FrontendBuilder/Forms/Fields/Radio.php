<?php

namespace Soda\Cms\FrontendBuilder\Forms\Fields;

use Soda\Cms\FrontendBuilder\Forms\AbstractFormField;

class Radio extends AbstractFormField
{
    protected $view = 'radio';

    public function getDefaultParameters()
    {
        return [
            'options' => [],
        ];
    }
}
