<?php

/**
 * Facade for Orchestrating Router_Web Routing
 */

namespace IFM;

class Router_Web
{

	protected static $routes = array();

	public static function register()
	{
		$router = new Router_Web_Router(IFM_NAMESPACE);
		$routes = self::$routes;
		Router_Web_Processor::init($router, $routes);
	}

	public static function render(string $uri, string $callback)
	{
		self::add_route($uri, '', 'ifm-main', $callback);
	}

	protected static function add_route(string $uri, string $hook, string $template, string $callback)
	{
		self::$routes[$callback] = new Router_Web_Route($uri, '', $template);
	}
}
