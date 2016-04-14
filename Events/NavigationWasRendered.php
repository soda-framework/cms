<?php
namespace Soda\Events;

use App\Events\Event;

class NavigationWasRendered extends Event{
    public function __construct($input = NULL){
        if($input){
            $this->input = $input;
        }
    }
}