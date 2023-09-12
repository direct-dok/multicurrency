<?php

namespace App\Account;

use App\Entity\Entity;
use App\Currencies\AccountsCurrencies;

class Account extends Entity
{

    protected $pk = null;

    public function create_account()
    {
        $account_name = uniqid('Account_', true);

        $params = [
            'name' => $account_name
        ];

        $this->db->query('INSERT INTO `accounts` (name) VALUES (:name)', $params);
        $this->pk = $this->db->get_last_insert_id();

        return $this;
    }

    public function add_currency($name_currency)
    {
        $params = [
            'name' => $name_currency
        ];

        $result = $this->db->getRow('currencies', ' WHERE name = :name', $params);

        $currency_id = $result['id'];
        $account_id = $this->get_pk();

//        $accounts_currencies = new AccountsCurrencies();
//        $accounts_currencies->set_currency($account_id, $currency_id);

        return $result;
    }
}