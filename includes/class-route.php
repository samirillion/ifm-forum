<?php

namespace IFM;

class Route
{
    public static function register()
    {
        Router_Web::register();
        Router_Api::register();
        Router_Qvars::register();
    }
    public static function get($uri, $callback)
    {
        // Default to Routing to the Index Template
        Router_Web::render($uri, $callback);
        Router_Api::get($uri, $callback);
    }
    public static function render($uri, $callback)
    {
        Router_Web::render($uri, $callback);
        return __CLASS__;
    }
    public static function json_api($uri, $callback, $method = "WP_REST_Server::READABLE")
    {
        Router_Api::{$method}($uri, $callback);
        return __CLASS__;
    }
    public static function add_query_var($query_var)
    {
        Router_Qvars::add_var($query_var);
    }
    public static function add_query_vars(array $query_vars)
    {
        Router_Qvars::add_vars($query_vars);
    }
    public static function permission_callback(string $minimum_level)
    {
        $min_level = $minimum_level;
        return $min_level;
    }
}
