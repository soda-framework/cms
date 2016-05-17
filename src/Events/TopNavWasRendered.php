<?php
namespace Soda\Events;


class TopNavWasRendered {
    public function __construct($input = NULL){
        if($input){
            $this->input = $input;
        }
    }
}
