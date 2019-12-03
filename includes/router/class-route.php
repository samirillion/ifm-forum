<?php
/**
 * Ifm Route
 */
class IfmRoute {

	protected static $routes = array();
	protected $router;

	public static function register() {
		$namespace = IFM_NAMESPACE;
		require( IFM_INC . 'router/class-router.php' );
		new IfmRouter( $namespace, self::$routes );
	}

	public static function get( string $uri, string $callback = null ) {
		self::add_route( 'get', $uri, $callback );
		return __CLASS__;
	}
	public static function post( string $uri, string $callback = null ) {
		self::add_route( 'post', $uri, $callback );
		return __CLASS__;
	}

	public static function auth( string $minimum_level ) {
		$route = end( self::$routes );
		self::update_last_route( 'auth', $minimum_level );
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
		$route                  = $fields[ $index ];
		$route[ $key ]          = $value;
		self::$routes[ $index ] = $route;
	}
}
