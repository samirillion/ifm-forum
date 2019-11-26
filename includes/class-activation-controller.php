<?php
/**
 * Class For activation
 *
 * @package IfmSort
 */
class IfmActivationController {

	/**
	 * Registration Functions.
	 */
	public static function register() {
		// $plugin = new self();

		// should put this elsewhere I suppose
		show_admin_bar( false );
		// add_filter('cron_schedules', array($plugin, 'my_cron_schedules'));
	}

	/**
	 * Define schedule for search indexing.
	 */
	public function my_cron_schedules( $schedules ) {
		if ( ! isset( $schedules['5min'] ) ) {
			$schedules['5min'] = array(
				'interval' => 5 * 60,
				'display'  => __( 'Once every 5 minutes' ),
			);
		}
		return $schedules;
	}

	/**
	 * Plugin activation hook registered in bootstrap.php.
	 */
	public static function plugin_activated() {
		if ( ! wp_next_scheduled( 'post_ranking_cron' ) ) {
			wp_schedule_event( time(), '5min', 'post_ranking_cron' );
		}

		require_once( 'models/page-definitions.php' );
		$page_definitions = new IfmPageDefinitions;
		$page_definitions->define_pages();
	}
}

IfmActivationController::register();
