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
		return IFM_API_PREFIX;
	}

	public function register_routes() {
		foreach ( $this->routes as $method => $routes ) :
			foreach ( $routes as $route ) :
				$this->route = $route;
				$this->register_route( $method );
			endforeach;
		endforeach;
	}

	// Register our routes.
	protected function register_route( string $method ) {
		register_rest_route(
			$this->namespace,
			$this->route['uri'],
			array(
				// Here we register the readable endpoint for collections.
				array(
					'methods'             => $method,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'member_auth' ),
				),
			)
			);
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
