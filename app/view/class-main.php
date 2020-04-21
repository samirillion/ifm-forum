<?php

namespace IFM;

class View_Main
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

        // hard code nav into headers, should make more extensible later
        $header_views = array(IFM_VIEW . '/layout/nav.php');
        $footer_views = array();
        $html = $this->handler_before($header_views);
        $html .= call_user_func(array($instance, $method));
        $html .= $this->handler_after($footer_views);
        return $html;
    }

    protected function handler_before($views = array(), $html = '')
    {
        if (!empty($views)) {
            foreach ($views as $view) {
                ob_start();
                include_once($view);
                $html .= ob_get_clean();
            }
        }
        return $html;
    }

    protected function handler_after($views = array(), $html = '')
    {
        if (!empty($views)) {
            foreach ($views as $view) {
                ob_start();
                include_once($view);
                $html .= ob_get_clean();
            }
        }
        return $html;
    }
}
