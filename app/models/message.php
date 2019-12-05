<?php
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
