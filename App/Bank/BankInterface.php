<?php

namespace App\Bank;

use App\Account\AccountInterface;

interface BankInterface
{
    public function openNewAccount($name);
    public function disableCurrency(string $nameCurrency);
}