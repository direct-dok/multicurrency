<?php

namespace App\Bank;

interface BankInterface
{
    public function openNewAccount();

    public function disableCurrency(string $nameCurrency);
}