<?php

/**
 * Add All Assets Necessary for the Frontend
 */
add_action('wp_enqueue_scripts', function () {

    wp_enqueue_style('style.css', plugins_url('app/views/assets/style.css', __FILE__), null);
    wp_register_script('news-aggregator', plugins_url('app/views/assets/js/main.js', __FILE__), array('jquery'));
    wp_register_script('toggle-switch', plugins_url('app/views/assets/js/toggle-switch.js', __FILE__), array('jquery'));
    wp_localize_script(
        'news-aggregator',
        'myAjax',
        array(
            'ajaxurl'     => admin_url('admin-ajax.php'),
            'noposts'     => esc_html__('No older posts found', 'aggregator'),
            'ifm_tax' => get_query_var('ifm_tax'),
            'loggedIn'  => is_user_logged_in(),
            'loginPage' => home_url('member-login'),
        )
    );
    wp_enqueue_script('toggle-switch');
    wp_enqueue_script('news-aggregator');
});
