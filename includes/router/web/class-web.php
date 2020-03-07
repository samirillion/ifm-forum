<?php

/**
 * Facade for Orchestrating Web Routing
 */

namespace IFM;

class Web
{

	protected static $routes = array();

	public static function register()
	{
		$router = new Router(IFM_NAMESPACE);
		$routes = self::$routes;
		Processor::init($router, $routes);
	}

	public static function render(string $uri, string $callback)
	{
		self::add_route($uri, '', 'ifm-main', $callback);
	}

	protected static function add_route(string $uri, string $hook, string $template, string $callback)
	{
		self::$routes[$callback] = new Route($uri, '', $template);
	}
}
