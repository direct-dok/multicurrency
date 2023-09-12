<?php

namespace App\Currencies;

use App\Entity\Entity;

class AccountsCurrencies extends Entity
{
    public function set_currency(int $account_id, int $currency_id)
    {
        $params = [
            'account_id' => $account_id,
            'currency_id' => $currency_id,
        ];

        $this->db->query('INSERT INTO `accounts_currencies` (accout_id, currency_id) VALUES (:account_id, :currency_id)', $params);
    }
}