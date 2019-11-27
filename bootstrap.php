<?php
/**
 * Ifm Sorter
 *
 * @package Ifm
 * @link https://foodinneighborhoods.com/connect
 * @since 1.0.0
 *
 * Plugin Name: Ideal Forum
 * Plugin URI: https://github.com/samirillion/crowdsorter
 * Description: A Reddit-like forum plugin
 * Version:     1.0.0
 * Author:      samirillion
 * Author URI:  https://idealforum.org
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: Ifm
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'IFM_REQUIRED_PHP_VERSION', '5.3' ); // because of get_called_class()
define( 'IFM_REQUIRED_WP_VERSION', '3.0' );
define( 'IFM_REQUIRED_WP_NETWORK', false ); // because plugin is not compatible with WordPress multisite

/**
 * Checks if the system requirements are met
 *
 * @since    1.0.0
 * @return bool True if system requirements are met, false if not
 */
function ifm_requirements_met() {

	global $wp_version;

	if ( version_compare( PHP_VERSION, IFM_REQUIRED_PHP_VERSION, '<' ) ) {
		return false;
	}
	if ( version_compare( $wp_version, IFM_REQUIRED_WP_VERSION, '<' ) ) {
		return false;
	}
	if ( is_multisite() !== IFM_REQUIRED_WP_NETWORK ) {
		return false;
	}

	return true;

}

/**
 * Prints an error that the system requirements weren't met.
 *
 * @since    1.0.0
 */
function ifm_show_requirements_error() {

	global $wp_version;
	require_once( dirname( __FILE__ ) . '/views/admin/errors/requirements-error.php' );

}

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_ifm() {

	/**
	 * Check requirements and load main class
	 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met.
	 * Otherwise older PHP installations could crash when trying to parse it.
	 */
	if ( ifm_requirements_met() ) {

		define( 'IFM_BASE_PATH', plugin_dir_path( __FILE__ ) );

		require_once( IFM_BASE_PATH . '/activate/class-activate.php' );
		require_once( IFM_BASE_PATH . '/app/class-posts-controller.php' );
		require_once( IFM_BASE_PATH . '/app/class-user-controller.php' );
		require_once( IFM_BASE_PATH . '/app/class-comment-controller.php' );
		register_activation_hook( __FILE__, array( 'Ifmsort', 'plugin_activated' ) );

	} else {

		add_action( 'admin_notices', 'ifm_show_requirements_error' );
		require_once( ABSPATH . 'wp-admin/app/plugin.php' );
		deactivate_plugins( plugin_basename( __FILE__ ) );

	}

}
run_ifm();
