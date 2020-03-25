<?php

namespace IFM;

class Controller_Messaging
{
    public function inbox()
    {
        $inbox = Model_Message::query()->find();
        require_once(IFM_APP . 'view/class-inbox.php');
        return Inbox::render($inbox);
    }
}
