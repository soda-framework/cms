<?php

namespace Soda\Cms\Events;

class TopNavDropdownWasRendered
{
    public function __construct($input = null)
    {
        if ($input) {
            $this->input = $input;
        }
    }
}
