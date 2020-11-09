<?php

namespace IFM;

class Controller_Mailbox
{
    public function main()
    {
        if (!is_user_logged_in()) {
            $this->redirect_to_login;
        }

        $query = new Model_Query(array('private' => true));

        return view('mailbox/main', array('query' => $query));
    }
}
