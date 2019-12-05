<?php
require(IFM_INC . 'router/web/class-web.php');
require(IFM_INC . 'router/api/class-api.php');

/**
 * Facade class for Orchestrating The Web and API Routers
 */
class IfmRoute
{
    public static function register()
    {
        IfmWeb::register();
        IfmApi::register();
    }
    public static function get($uri, $callback)
    {
        // Default to Routing to the Index Template
        IfmWeb::render($uri, $callback);
        IfmApi::get($uri, $callback);
    }
    public static function render($uri, $callback)
    {
        IfmWeb::render($uri, $callback);
    }
    public static function json_api($uri, $callback, $method = "get")
    {
        IfmApi::{$method}($uri, $callback);
    }
}
