<?php
/**
 * Post Edit Render Class
 */
class CrowdEditPost {

public static function render() {
		wp_enqueue_style( 'style.css', plugin_dir_url( __FILE__ ) . '/assets/style.css', null );
		wp_register_script( 'news-aggregator', plugin_dir_url( __FILE__ ) . '/assets/js/main.js', array( 'jquery' ) );
		wp_localize_script(
		'news-aggregator',
		'myAjax',
		array(
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'noposts'   => esc_html__( 'No older posts found', 'aggregator' ),
			'loggedIn'  => is_user_logged_in(),
			'loginPage' => home_url( 'member-login' ),
		)
		);
		wp_enqueue_script( 'news-aggregator' );
		ob_start();
		require_once( 'partials/edit-post.php' );
		$output = ob_get_clean();

		echo $output;

	}
}
