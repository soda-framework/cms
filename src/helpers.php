<?php

if (!function_exists('soda_cms_view_path')) {
    function soda_cms_view_path($view)
    {
        return config('soda.cms.hint').'::'.$view;
    }
}

if (!function_exists('soda_cms_view')) {
    function soda_cms_view($view, $params = [])
    {
        return view(soda_cms_view_path($view), $params);
    }
}

if (!function_exists('truncate_words')) {
    function truncate_words($string, $wordsreturned)
    {
        $retval = $string;
        $string = preg_replace('/(?<=\S,)(?=\S)/', ' ', $string);
        $string = str_replace("\n", " ", $string);
        $array = explode(" ", $string);
        if (count($array) <= $wordsreturned) {
            $retval = $string;
        } else {
            array_splice($array, $wordsreturned);
            $retval = implode(" ", $array)." ...";
        }

        return $retval;
    }
}

if (!function_exists('soda_request_is')) {
    function soda_request_is($path = '')
    {
        $path = $path !== '' ? '/' . ltrim($path, '/') : $path;

        return Request::is(config('soda.cms.path').$path);
    }
}
