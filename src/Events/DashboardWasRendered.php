<?php
namespace Soda\Events;


class DashboardWasRendered {
    public function __construct($input = NULL){
        if($input){
            $this->input = $input;
        }
    }
}