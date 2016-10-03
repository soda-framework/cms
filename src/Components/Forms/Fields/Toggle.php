<?php

namespace Soda\Cms\Components\Forms\Fields;

class Toggle extends Checkbox
{
    protected $view = "toggle";

    public function getDefaultParameters()
    {
        return [
            'checked-value'   => 1,
            'unchecked-value' => 0,
        ];
    }

    public function renderForTable()
    {
        $parameters = $this->parseFieldParameters();
        $value = $this->getFieldValue();

        return $value == $parameters['checked-value'] ? 'Enabled' : 'Disabled';
    }
}
