<?php
class IfmModel
{
    protected $entity_type;
    protected $current_user;

    public function __construct($entity_type = 'post', $current_user = false, $args = array())
    {
        $this->entity_type = $entity_type;
        $this->current_user = $current_user;
    }
    public function getMany()
    { }
}
