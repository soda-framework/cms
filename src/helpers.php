<?php

use Illuminate\Contracts\Routing\ResponseFactory;

if (! function_exists('resolve_class')) {
    /**
     * Get the concrete classname for an abstract binding.
     *
     * @param $abstract
     *
     * @return string
     */
    function resolve_class($abstract)
    {
        return get_class(app($abstract));
    }
}

if (! function_exists('soda_cms_view_path')) {
    /**
     * @param $view
     *
     * @return string
     */
    function soda_cms_view_path($view)
    {
        return config('soda.cms.hint').'::'.$view;
    }
}

if (! function_exists('soda_cms_view')) {

    /**
     * @param string $view
     * @param array  $params
     *
     * @return \Illuminate\Http\Response
     */
    function soda_cms_view($view, $params = [])
    {
        return app(ResponseFactory::class)->view(soda_cms_view_path($view), $params);
    }
}

if (! function_exists('truncate_words')) {

    /**
     * @param string $string
     * @param int    $wordsreturned
     *
     * @return string
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
    /**
     * @param string $path
     *
     * @return bool
     */
    function soda_request_is($path = '')
    {
        $path = $path !== '' ? '/'.ltrim($path, '/') : $path;
        $path = ltrim(config('soda.cms.path').$path, '/');

        return Request::is($path === '' ? '/' : $path);
    }
}
