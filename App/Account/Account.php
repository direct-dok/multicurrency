<?php

namespace App\Account;

class Account
{
    protected $name = 'Account 1';

    public function get_name()
    {
        return $this->name;
    }
}