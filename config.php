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
define('IFM_BASE', plugin_dir_path(__FILE__));
define('IFM_APP', IFM_BASE . 'app/');
define('IFM_INC', IFM_BASE . 'includes/');
define('IFM_ASSET', IFM_BASE . 'app/view/assets/');
define('IFM_VIEW', IFM_BASE . 'app/view/');

/**
 * Define Post Types
 */
define('IFM_POST_TYPE', 'ifm-post');
define('IFM_POST_TAXONOMY_NAME', 'ifm-tax');

/**
 * Set to determine the base namespace for the router. 
 */
define('IFM_API_PREFIX', 'api');
define('IFM_NAMESPACE', 'ifm');

add_filter('rest_url_prefix', function ($prefix) {
    return IFM_API_PREFIX;
});

/**
 * Define Base Routes
 */
define('IFM_ROUTE_FORUM', '/' . IFM_NAMESPACE . '/forum');
define('IFM_ROUTE_COMMENTS', '/' . IFM_NAMESPACE . '/comments');
define('IFM_ROUTE_INBOX', '/' . IFM_NAMESPACE . '/inbox');
define('IFM_ROUTE_ACCOUNT', '/' . IFM_NAMESPACE . '/account');
define('IFM_ROUTE_CREATE', '/' . IFM_NAMESPACE . '/create');
