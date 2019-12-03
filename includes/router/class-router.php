<?php
/**
 * Ifm Route
 */
require_once( IFM_APP . 'controllers/class-comment-controller.php' );
require_once( IFM_APP . 'controllers/class-posts-controller.php' );
require_once( IFM_APP . 'controllers/class-user-controller.php' );

class IfmRouter {

	protected $routes;
	protected $namespace;
	protected $route;
	protected $controllers;

	public function __construct( array $routes ) {
		$this->namespace = IFM_NAMESPACE;
		$this->routes    = $routes;

		/**
		 * Two WordPress Hooks to Define the Location of Stuff
		 */
		add_filter( 'rest_url_prefix', array( $this, 'custom_api_prefix' ) );
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function custom_api_prefix( $slug ) {
		xdebug_break();
		return IFM_API_PREFIX;
	}

	public function register_routes() {
		foreach ( $this->routes as $route ) :
			$this->register_route( $route );
		endforeach;
	}

	// Register our routes.
	protected function register_route( array $route ) {
		$permission_callback = $route['permission_callback'];
		$controller          = explode( '@', $route['callback'] )[0];
		$method              = explode( '@', $route['callback'] )[1];
		// Instantiate Controller Once
		if ( ! in_array( $controller, $this->controllers ) ) :
			$this->controllers[ $controller ] = new $controller;
		endif;
		register_rest_route(
			$this->namespace,
			$route['uri'],
			array(
				'methods'             => $route['method'],
				'callback'            => array( $this->controllers[ $controller ], $method ),
				'permission_callback' => array( $this, 'member_auth' ),
			)
		);
	}

	public function member_auth() {
		return true;
	}

	// Sets up the proper HTTP status code for authorization.
	public function authorization_status_code() {

	$status = 401;

	if ( is_user_logged_in() ) {
			$status = 403;
		}

	return $status;
	}
}
