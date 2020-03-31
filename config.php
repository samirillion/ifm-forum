<?php

/**
 * Set your app-specific constants here
 */
define('IFM_REQUIRED_PHP_VERSION', '5.3'); // because of get_called_class()
define('IFM_REQUIRED_WP_VERSION', '5.0');
define('IFM_REQUIRED_WP_NETWORK', false); // because plugin is not compatible with WordPress multisite

/**
 * These Are just paths to directories to make the code a bit DRYer
 */
define('IFM_BASE_PATH', plugin_dir_path(__FILE__));
define('IFM_APP', IFM_BASE_PATH . 'app/');
define('IFM_INC', IFM_BASE_PATH . 'includes/');
define('IFM_ASSET', IFM_BASE_PATH . 'app/view/assets/');
define('IFM_VIEW', IFM_BASE_PATH . 'app/view/');

/**
 * Set to determine the base namespace for the router. 
 */
define('IFM_API_PREFIX', 'api');
define('IFM_NAMESPACE', 'ifm');

add_filter('rest_url_prefix', function ($prefix) {
    return IFM_API_PREFIX;
});

// Define Routes
define('IFM_ROUTE_POSTS', 'connect');
define('IFM_ROUTE_CREATE_POST', 'create-post');
define('IFM_ROUTE_COMMENTS', 'comments');
define('IFM_ROUTE_INBOX', 'my-inbox');
define('IFM_ROUTE_MY_ACCOUNT', 'my-account');
