<?php

namespace IFM;

class Controller_PM
{
    public function main()
    {
        if (!is_user_logged_in()) {
            $this->redirect_to_login;
        }

        $query = new Model_Query(array('private' => true));

        return view('pm/main', array('query' => $query));
    }
}
