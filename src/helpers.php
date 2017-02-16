<?php

if (! function_exists('truncate_words')) {
    function truncate_words($string, $wordsreturned)
    {
        $retval = $string;
        $string = preg_replace('/(?<=\S,)(?=\S)/', ' ', $string);
        $string = str_replace("\n", ' ', $string);
        $array = explode(' ', $string);
        if (count($array) <= $wordsreturned) {
            $retval = $string;
        } else {
            array_splice($array, $wordsreturned);
            $retval = implode(' ', $array).' ...';
        }

        return $retval;
    }
}
