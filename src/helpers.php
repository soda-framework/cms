<?php
/**
 * Get the concrete classname for an abstract binding.
 *
 * @param $abstract
 *
 * @return string
 */
if (! function_exists('resolve_class')) {
    function resolve_class($abstract)
    {
        return get_class(app($abstract));
    }
}

if (! function_exists('soda_cms_view_path')) {
    function soda_cms_view_path($view)
    {
        return config('soda.cms.hint').'::'.$view;
    }
}

if (! function_exists('soda_cms_view')) {

    /**
     * @param string $view
     */
    function soda_cms_view($view, $params = [])
    {
        return view(soda_cms_view_path($view), $params);
    }
}

if (! function_exists('truncate_words')) {

    /**
     * @param string $string
     * @param integer $wordsreturned
     */
    function truncate_words($string, $wordsreturned)
    {
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

if (! function_exists('soda_request_is')) {
    function soda_request_is($path = '')
    {
        $path = $path !== '' ? '/'.ltrim($path, '/') : $path;
        $path = ltrim(config('soda.cms.path').$path, '/');

        return Request::is($path === '' ? '/' : $path);
    }
}
