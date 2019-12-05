<?php
require_once(IFM_APP)
class IfmMessage extends IfmModel
{
    protected $post_type = "";
    public function __construct($post_type)
    {
        $this->post_type = $post_type;
    }
    public function select($query_args)
    { }
}
