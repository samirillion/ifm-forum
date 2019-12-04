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
		$routes = self::$routes;
		$router = new IfmWebRouter(IFM_NAMESPACE, $routes);
		IfmProcessor::init($router, $routes);
	}

	public static function render(string $uri, string $template)
	{
		self::add_route($uri, '', $template);
	}

	protected static function add_route(string $uri, string $hoook, string $template)
	{
		self::$routes[] = new IfmRoute($uri, '', $template);
	}
}
