<?php
class IfmApiRouter
{

	protected $routes      = array();
	protected $namespace   = '';
	protected $route       = array();
	protected $controllers = array();

	public function __construct(array $routes)
	{
		$this->namespace = IFM_NAMESPACE;
		$this->routes    = $routes;

		/**
		 * Two WordPress Hooks to Define the Location of Stuff
		 */
		add_action('rest_api_init', array($this, 'register_routes'));
	}

	public function register_routes()
	{
		foreach ($this->routes as $route) :
			$this->register_route($route);
		endforeach;
	}

	// Register our routes.
	protected function register_route(array $route)
	{
		$permission_callback = in_array('permission_callback', $route) ? $route['permission_callback'] : 'no_auth';
		$controller          = explode('@', $route['callback'])[0];
		$method              = explode('@', $route['callback'])[1];
		// Instantiate Controller Once
		if (!in_array($controller, $this->controllers)) :
			$this->controllers[$controller] = new $controller;
		endif;

		register_rest_route(
			$this->namespace,
			$route['uri'],
			array(
				'methods'             => $route['method'],
				'callback'            => array($this->controllers[$controller], $method),
				'permission_callback' => array($this, $permission_callback),
			)
		);
	}

	public function at_least_member()
	{
		return false;
	}
	public function no_auth()
	{
		return true;
	}

	// Sets up the proper HTTP status code for authorization.
	public function authorization_status_code()
	{

		$status = 401;

		if (is_user_logged_in()) {
			$status = 403;
		}

		return $status;
	}
}
