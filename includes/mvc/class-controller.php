<?php
class IfmController
{
    public static function get_params($params = array())
    {
        foreach (IfmQueryVars::$query_vars as $var) :
            if (get_query_var($var, false)) :
                $params[$var] = get_query_var($var);
            endif;
        endforeach;
        return $params;
    }
}
