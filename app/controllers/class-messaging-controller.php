<?php
require(IFM_APP . 'models/message.php');

class IfmMessagingController
{
    public function inbox()
    {
        $inbox = IfmMessage::query()->find();
        require_once(IFM_APP . 'views/class-inbox.php');
        return IfmInbox::render($inbox);
    }
}
