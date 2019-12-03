<?php
/**
 * Ifm Route
 */
require( IFM_INC . 'router/class-router.php' );

class IfmRoute {

	protected static $routes = array();
	protected $router;

	public static function register() {
		new IfmRouter( self::$routes );
	}

	public static function get( string $uri, string $callback = null ) {
		self::add_route( 'GET', $uri, $callback );
		return __CLASS__;
	}
	public static function post( string $uri, string $callback = null ) {
		self::add_route( 'POST', $uri, $callback );
		return __CLASS__;
	}

	public static function auth( string $minimum_level ) {
		self::update_last_route( 'permission_callback', $minimum_level );
		return __CLASS__;
	}

	protected static function add_route( string $method, string $uri = null, string $callback = null ) {
		if ( isset( $uri ) && isset( $callback ) ) :
				self::$routes[] = array(
					'method'   => $method,
					'uri'      => $uri,
					'callback' => $callback,
				);
		endif;
	}

	protected static function update_last_route( $key, $value ) {
		$index                  = count( self::$routes ) - 1;
		$route                  = self::$routes[ $index ];
		$route[ $key ]          = $value;
		self::$routes[ $index ] = $route;
	}
}
