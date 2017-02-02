<?php

namespace Soda\Cms\Foundation\Events;

use Soda\Cms\Foundation\Forms\FormFieldInterface;

class FieldIsRendering
{
    public $formField;

    /**
     * Create a new event instance.
     *
     * @param FormFieldInterface $formField
     */
    public function __construct(FormFieldInterface $formField)
    {
        $this->formField = $formField;
    }
}
