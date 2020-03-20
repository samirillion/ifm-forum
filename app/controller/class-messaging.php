<?php

namespace IFM;
// require(IFM_APP . 'models/message.php');

class Controller_Messaging
{
    public function inbox()
    {
        $inbox = Message::query()->find();
        require_once(IFM_APP . 'views/class-inbox.php');
        return Inbox::render($inbox);
    }
}
