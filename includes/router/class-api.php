<?php

namespace IFM;


class Router_Api
{

	protected static $routes = array();
	protected $router;

	public static function register()
	{
		new Router_Api_Router(self::$routes);
	}

	public static function get(string $uri, string $callback = null)
	{
		self::add_route(\WP_REST_Server::READABLE, $uri, $callback);
		return __CLASS__;
	}
	public static function post(string $uri, string $callback = null)
	{
		self::add_route(\WP_REST_Server::CREATABLE, $uri, $callback);
		return __CLASS__;
	}

	public static function add_route(string $uri = null, string $callback = null, $method, $auth)
	{
		$method = "GET" == \strtoupper($method) ? "WP_REST_Server::READABLE" : "WP_REST_Server::CREATABLE";

		if (isset($uri) && isset($callback)) :
			self::$routes[] = array(
				'method'   => $method,
				'uri'      => $uri,
				'callback' => $callback,
				'auth'     => $auth
			);
		endif;
	}

	public static function permission_callback(string $minimum_level)
	{
		self::update_last_route('permission_callback', $minimum_level);
		return __CLASS__;
	}

	protected static function update_last_route($key, $value)
	{
		$index                  = count(self::$routes) - 1;
		$route                  = self::$routes[$index];
		$route[$key]          = $value;
		self::$routes[$index] = $route;
	}
}
