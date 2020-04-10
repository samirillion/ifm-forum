<?php

namespace IFM;

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

class Forum
{
	/**
	 * Begins execution of the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function run()
	{
		// Require config file
		require(plugin_dir_path(__FILE__) . 'config.php');

		/**
		 * Check requirements and load main class
		 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met.
		 * Otherwise older PHP installations could crash when trying to parse it.
		 */
		if (self::requirements_met()) {

			// Autoload Vendor Classes
			require(IFM_BASE . 'vendor/autoload.php');

			// Autoload everything Else
			spl_autoload_register(array(new self, 'autoload'), true, false);

			register_activation_hook(__FILE__, 'ifm\Singleton_Activation::forum_activated');

			Singleton_Custompost::register();

			require_once(IFM_APP . 'controller/class-comment.php');
			require_once(IFM_APP . 'controller/class-account.php');
			require_once(IFM_APP . 'controller/class-forum.php');

			require(IFM_BASE . 'enqueue.php');

			require(IFM_INC . 'helpers.php');

			require(IFM_APP . 'routes.php');
		} else {

			add_action('admin_notices', array('IFM\Plugin\show_requirements_error'));
			require_once(ABSPATH . 'wp-admin/app/plugin.php');
			deactivate_plugins(plugin_basename(__FILE__));
		}
	}

	/**
	 * Checks if the system requirements are met
	 *
	 * @since    1.0.0
	 * @return bool True if system requirements are met, false if not
	 */
	private static function requirements_met()
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
	 * Handles autoloading of the IFM Forum classes.
	 *
	 * @param string $class
	 */
	public static function autoload($class)
	{
		if (0 !== strpos($class, 'IFM')) {
			return;
		}
		// autoloader works as follows:
		// 1. Checks `app` and `includes` directories
		// 2. From inside those directories, class name matches directory structure, e.g., class Path_To_Name
		// 3. Checks for path/to/class-name.php, and includes
		$class = str_replace("IFM\\", "", $class);
		$exploded_class = explode("_", $class);
		$exploded_class[sizeof($exploded_class) - 1] = 'class-' . end($exploded_class);
		$app_path = IFM_APP . strtolower(implode(DIRECTORY_SEPARATOR, $exploded_class)) . '.php';
		$inc_path = IFM_INC . strtolower(implode(DIRECTORY_SEPARATOR, $exploded_class)) . '.php';
		if (file_exists($app_path) && include_once($app_path)) {
			return TRUE;
		} elseif (file_exists($inc_path) && include_once($inc_path)) {
			return TRUE;
		} else {
			trigger_error("The class '$class' or the file '$app_path' or '$inc_path' failed to spl_autoload  ", E_USER_WARNING);
			return FALSE;
		}
	}

	/**
	 * Prints an error that the system requirements weren't met.
	 *
	 * @since    1.0.0
	 */
	function show_requirements_error()
	{
		global $wp_version;
		require_once(dirname(__FILE__) . '/view/admin/errors/requirements-error.php');
	}
}
Forum::run();
