<?php

namespace IFM;

if (!function_exists('ifm\view')) {
    function view($view = null, $data = [], $params = [])
    {
        ob_start();
        $data;
        $params;
        require_once(IFM_VIEW . $view . '.php');
        return ob_get_clean();
    }
}
