<?php

namespace App\Bank;

use App\Account\AccountInterface;
use App\Bank\BankInterface;

class Bank implements BankInterface
{
    private $account;
    private $currencies = [];
    protected $mainCurrency;

    public function __construct(AccountInterface $account)
    {
        $this->account = $account;
    }


    public function openNewAccount()
    {

    }

    public function listSupportedCurrencies()
    {
        $namesCurrencies = array_keys($this->currencies);
        echo '[' . implode(', ', $namesCurrencies) . ']';
    }

    public function addCurrency(AbstractCurrency $currency)
    {
        $this->currencies[$currency->getName()] = $currency;
    }

    public function setMainCurrency(string $nameCurrency)
    {
        $this->mainCurrency = $nameCurrency;
    }

    public function getMainCurrency()
    {
        return $this->currencies[$this->mainCurrency];
    }

    public function disableCurrency(string $nameCurrency)
    {
        // TODO: Implement disableCurrency() method.
    }

    public function getBalance()
    {
        $currency = $this->currencies[$this->getMainCurrency()];

        if (!$currency) {
            throw new \Exception();
        }

        return $currency->getBalance();
    }
}