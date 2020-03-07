<?php
class View
{
    public static function render(string $callback)
    {
        $template = new self();
        echo $template->run_handler($callback);
    }

    protected function run_handler($callback)
    {
        $handler = explode('@', $callback);
        $controller = new $handler[0];
        $html = call_user_func(array($controller, $handler[1]));
        return $html;
    }
}
