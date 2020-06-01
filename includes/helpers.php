<?php

namespace IFM;

use WP_Query;

if (!function_exists('ifm\view')) {
    function view($view = null, $query, $params = [])
    {
        ob_start();
        $query;
        $params;
        require_once(IFM_VIEW . $view . '.php');
        return ob_get_clean();
    }
}

if (!function_exists('ifm\pagination')) :
    function pagination($page = '', $max_page = '', \WP_Query $query)
    {
        $big = 999999999; // need an unlikely integer
        if (!$page)
            $page = get_query_var('ifm_p');
        if (!$max_page)
            $max_page = $query->max_num_pages;

        $base_url = remove_query_arg('ifm_p');

        $pagination = "<div class='ifm-pagination'>";

        for ($i = 1; $i <= $max_page; $i++) {
            $class = 'ifm-page';

            if ($i == $page) {
                $class .= ' ifm-current-page';
            }

            if ($i != $page && 1 == $i) {
                $pagination .= "<a class='ifm-prev ifm-arrow' href='" . add_query_arg('ifm_p', $page - 1, $base_url) . "'><<</a>";
            }
            $pagination .= "<a href='" . add_query_arg('ifm_p', $i, $base_url) . "' class='"  . esc_attr($class) . "'>" . $i . "</a>";

            if ($i == $max_page && $page != $max_page) {
                $pagination .= "<a class='ifm-next ifm-arrow' href='" . add_query_arg('ifm_p', $page + 1, $base_url) . "'>>></a>";
            }
        }

        $pagination .= '</div>';

        echo $pagination;
    }
endif;
