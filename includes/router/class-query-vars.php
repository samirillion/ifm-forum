<?php
class IfmQueryVars
{
    public static $query_vars = array();

    public static function register()
    {
        $plugin = new self();
        add_filter('query_vars', array($plugin, 'register_custom_vars'));
    }

    public function register_custom_vars($qvars)
    {
        foreach (self::$query_vars as $var) :
            $qvars[] = $var;
        endforeach;
        return $qvars;
    }

    public static function add_vars(array $vars)
    {
        self::$query_vars = array_merge(self::$query_vars, $vars);
    }

    public static function add_var($var)
    {
        self::$query_vars[] = $var;
        return __CLASS__;
    }
}
