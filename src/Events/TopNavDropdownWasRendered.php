<?php
namespace Soda\Cms\Events;


class TopNavDropdownWasRendered {
    public function __construct($input = NULL){
        if($input){
            $this->input = $input;
        }
    }
}
