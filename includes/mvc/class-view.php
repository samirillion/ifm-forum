<?php

namespace IFM;

class Mvc_View
{
    public static function render(string $callback)
    {
        $template = new self();
        echo $template->run_handler($callback);
    }

    protected function run_handler($callback)
    {
        $handler = explode('@', $callback);
        $class = __NAMESPACE__ . '\\' . $handler[0];
        $instance = new $class;
        $method = $handler[1];
        $html = call_user_func(array($instance, $method));
        return $html;
    }
}
