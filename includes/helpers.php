<?php

namespace IFM;

use WP_Query;

if (!function_exists('ifm\view')) {
    function view($view = null, $query = null, $params = [])
    {
        ob_start();
        $query;
        $params = array_merge(array(
            'main' => false,
        ), $params);

        require_once(IFM_VIEW . '/layout/nav.php');
        echo "<div class='ifm-body'>";
        require_once(IFM_VIEW . $view . '.php');
        echo "</div>";
        return ob_get_clean();
    }
}

/**
 * Function to Parse the callback string to add the controller class and method as CSS classes to the forum wrapper
 *
 * @param string $callback
 * @return string
 */
if (!function_exists('ifm\main_classes')) :
    function main_classes($callback)
    {
        $exploded = explode("@", $callback);
        $controller = $exploded[0];
        $method = $exploded[1];

        if (substr($controller, 0, strlen('Controller_')) == 'Controller_') {
            $controller = strtolower(substr($controller, strlen('Controller_')));
        }

        return \esc_attr("ifm-" . $controller . " " . "ifm-" . $method);
    }
endif;

/**
 * Generate Custom Pagination
 *
 * @param string $page
 * @param string $max_page
 * @param \WP_Query $query
 * @return void
 */
if (!function_exists('ifm\pagination')) :
    function pagination($page = '', $max_page = '', \WP_Query $query)
    {
        $big = 999999999; // need an unlikely integer
        if (!$page)
            $page = get_query_var('ifm_p') ? get_query_var('ifm_p') : 1;
        if (!$max_page)
            $max_page = $query->max_num_pages;

        $base_url = remove_query_arg('ifm_p');

        $pagination = "<div class='ifm-pagination'>";

        if ($max_page > 1) {
            for ($i = 1; $i <= $max_page; $i++) {
                $class = 'ifm-page';

                if ($i == $page) {
                    $class .= ' ifm-current-page';
                }

                // if on first page
                if (1 != $page && 1 == $i && 1 != $max_page) {
                    $pagination .= "<a class='ifm-prev ifm-arrow' href='" . add_query_arg('ifm_p', $page - 1, $base_url) . "'><<</a>";
                }
                $pagination .= "<a href='" . add_query_arg('ifm_p', $i, $base_url) . "' class='"  . esc_attr($class) . "'>" . $i . "</a>";

                if ($i == $max_page && $page != $max_page) {
                    $pagination .= "<a class='ifm-next ifm-arrow' href='" . add_query_arg('ifm_p', $page + 1, $base_url) . "'>>></a>";
                }
            }
        }

        $pagination .= '</div>';

        echo $pagination;
    }
endif;

/**
 * Function to parse strings in routes into class method calls
 *
 * @param [type] $callback
 * @return void
 */
if (!function_exists('ifm\parse_render_method')) :
    function parse_render_method($callback)
    {
        $handler = explode('@', $callback);
        $class = __NAMESPACE__ . '\\' . $handler[0];
        $instance = new $class;
        $method = $handler[1];

        // hard code nav into headers, should make more extensible later
        // $header_views = array(IFM_VIEW . '/layout/nav.php');
        // $footer_views = array();
        // $html = $this->handler_before($header_views);
        $html = call_user_func(array($instance, $method));
        // $html .= $this->handler_after($footer_views);
        return $html;
    }
endif;
