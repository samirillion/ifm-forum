<?php
/**
 * Router that works with WordPress Hooks and Templates
 */
require( IFM_BASE_PATH . 'includes/router/class-processor.php' );
require( IFM_BASE_PATH . 'includes/router/class-route.php' );
require( IFM_BASE_PATH . 'includes/router/class-router.php' );

$router = new Router( 'ifm' );
$routes = array(
	'my_plugin_index'    => new Route( '/my-plugin', '', 'my-plugin-index' ),
	'my_plugin_redirect' => new Route( '/my-plugin/redirect', 'my_plugin_redirect' ),
);
Processor::init( $router, $routes );
function my_plugin_redirect() {
	 $location = '/';
	if ( ! empty( $_GET['location'] ) ) {
		$location = $_GET['location'];
	}
	wp_redirect( $location );
}
add_action( 'my_plugin_redirect', 'my_plugin_redirect' );
