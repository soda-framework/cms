<?php

namespace Soda\Cms\FrontendBuilder\Forms\Fields;

use Soda\Cms\FrontendBuilder\Forms\AbstractFormField;

class Dropdown extends AbstractFormField
{
    protected $view = 'dropdown';

    public function getDefaultParameters()
    {
        return [
            'options' => [],
        ];
    }
}
