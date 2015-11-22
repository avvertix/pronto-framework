<?php

use Illuminate\Support\Str;
use Illuminate\Container\Container;

if (! function_exists('pageview')) {
    /**
     * Get the evaluated page contents by rendering the page content in the given view.
     *
     * The page content will be passed to the view as the *content* parameter.
     *
     * @param  string  $page the path of the Markdown page to render
     * @param  string  $view the name of the view
     * @param  array   $data additional view data
     * @return \Illuminate\View\View
     */
    function pageview($page, $view = null, $data = [])
    {
        $content = app('Pronto\Markdown\Parser')->file($page);

        $data = array_merge(['content' => $content], $data);

        return view($view, $data);
    }
}

if (! function_exists('content_path')) {
    /**
     * Get the path to the content directory.
     *
     * @param  string  $path
     * @return string
     */
    function content_path($path = '')
    {
        return realpath(config('pronto.content_folder', storage_path('content')).($path ? '/'.$path : $path));
    }
}

if (! function_exists('image_path')) {
    /**
     * Get the path to the images directory.
     *
     * @param  string  $path
     * @return string
     */
    function image_path($path = '')
    {
        return realpath(config('pronto.image_folder', storage_path('images')).($path ? '/'.$path : $path));
    }
}

if (! function_exists('assets_path')) {
    /**
     * Get the path to the assets directory.
     *
     * @param  string  $path
     * @return string
     */
    function assets_path($path = '')
    {
        return realpath(config('pronto.assets_folder', storage_path('assets')).($path ? '/'.$path : $path));
    }
}

if (! function_exists('content')) {
    /**
     * Get the Content service (Pronto\Contracts\Content).
     *
     * @return Pronto\Contracts\Content
     */
    function content()
    {
        return app('Pronto\Contracts\Content');
    }
}