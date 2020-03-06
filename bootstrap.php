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
if (!defined('WPINC')) {
	die;
}

namespace IFM\Importer;

class Plugin
{
	/**
	 * Checks if the system requirements are met
	 *
	 * @since    1.0.0
	 * @return bool True if system requirements are met, false if not
	 */
	static private function ifm_requirements_met()
	{

		global $wp_version;

		if (version_compare(PHP_VERSION, IFM_REQUIRED_PHP_VERSION, '<')) {
			return false;
		}
		if (version_compare($wp_version, IFM_REQUIRED_WP_VERSION, '<')) {
			return false;
		}
		if (is_multisite() !== IFM_REQUIRED_WP_NETWORK) {
			return false;
		}

		return true;
	}

	/**
	 * Begins execution of the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function load()
	{

		require(plugin_dir_path(__FILE__) . 'config.php');

		require(IFM_BASE_PATH . 'enqueue.php');

		// revisit soon
		require(IFM_BASE_PATH . '/vendor/autoload.php');

		/**
		 * Check requirements and load main class
		 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met.
		 * Otherwise older PHP installations could crash when trying to parse it.
		 */
		if (self::ifm_requirements_met()) {

			register_activation_hook(__FILE__, 'ifm_activated');



			require(IFM_BASE_PATH . 'seeds/post-types.php');

			require(IFM_INC . 'wp-orm/wp-orm.php');

			require(IFM_INC . 'mvc/class-view.php');

			require(IFM_APP . 'controllers/class-posts-controller.php');
			require(IFM_APP . 'controllers/class-user-controller.php');
			require(IFM_APP . 'controllers/class-comment-controller.php');
			require(IFM_APP . 'controllers/class-messaging-controller.php');

			require(IFM_APP . 'routes.php');
		} else {

			add_action('admin_notices', array('IFM\Importer\Plugin', 'show_requirements_error'));
			require_once(ABSPATH . 'wp-admin/app/plugin.php');
			deactivate_plugins(plugin_basename(__FILE__));
		}
	}

	/**
	 * Redirect to Settings Page on Forum Activate
	 *
	 * @return void
	 */
	function plugin_activated()
	{
		// add redirect code here
	}

	/**
	 * Prints an error that the system requirements weren't met.
	 *
	 * @since    1.0.0
	 */
	function show_requirements_error()
	{

		global $wp_version;
		require_once(dirname(__FILE__) . '/views/admin/errors/requirements-error.php');
	}
}
