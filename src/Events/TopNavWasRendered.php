<?php

namespace Soda\Events;

class TopNavWasRendered
{
    public function __construct($input = null)
    {
        if ($input) {
            $this->input = $input;
        }
    }
}
