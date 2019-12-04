<?php

/**
 * Facade for Orchestrating Web Routing
 */
require(IFM_INC . 'router/web/class-processor.php');
require(IFM_INC . 'router/web/class-router.php');
require(IFM_INC . 'router/web/class-route.php');

class IfmWeb
{

	protected static $routes = array();

	public static function register()
	{
		$router = new IfmWebRouter(IFM_NAMESPACE);
		$routes = self::$routes;
		IfmWebProcessor::init($router, $routes);
	}

	public static function render(string $uri, string $callback)
	{
		self::add_route($uri, '', 'ifm-main', $callback);
	}

	protected static function add_route(string $uri, string $hoook, string $template, string $callback)
	{
		self::$routes[$callback] = new IfmWebRoute($uri, '', $template);
	}
}
