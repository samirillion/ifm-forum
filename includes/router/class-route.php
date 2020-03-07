<?php

namespace IFM;

class Route
{
    public static function register()
    {
        Web::register();
        Api::register();
        Query_Vars::register();
    }
    public static function get($uri, $callback)
    {
        // Default to Routing to the Index Template
        Web::render($uri, $callback);
        Api::get($uri, $callback);
    }
    public static function render($uri, $callback)
    {
        Web::render($uri, $callback);
        return __CLASS__;
    }
    public static function json_api($uri, $callback, $method = "WP_REST_Server::READABLE")
    {
        Api::{$method}($uri, $callback);
        return __CLASS__;
    }
    public static function add_query_var($query_var)
    {
        Query_Vars::add_var($query_var);
    }
    public static function add_query_vars(array $query_vars)
    {
        Query_Vars::add_vars($query_vars);
    }
    public static function permission_callback(string $minimum_level)
    {
        $min_level = $minimum_level;
        return $min_level;
    }
}
