<?php

namespace Soda\Cms\Events;

class DashboardWasRendered
{
    public function __construct($input = null)
    {
        if ($input) {
            $this->input = $input;
        }
    }
}
