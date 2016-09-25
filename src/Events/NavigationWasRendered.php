<?php
namespace Soda\Cms\Events;

class NavigationWasRendered
{

    public function __construct($input = null)
    {
        if ($input) {
            $this->input = $input;
        }
    }
}
