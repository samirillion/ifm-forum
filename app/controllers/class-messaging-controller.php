<?php
class IfmMessagingController
{
    public function main()
    {
        $message = new Message();
        $inbox_contents = $message->getMany();
        require_once(IFM_APP . 'views/class-inbox.php');
        return IfmInbox::render($inbox_contents);
    }
}
