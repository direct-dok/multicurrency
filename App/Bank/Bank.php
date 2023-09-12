<?php

namespace App\Bank;

use App\Bank\BankInterface;
use App\Currency\Currency;

class Bank implements BankInterface
{
    private $accountName;
    private $currencies = [];
    protected $mainCurrency;

    public function openNewAccount($name)
    {
        $this->accountName = $name;
        return $this;
    }

    public function listSupportedCurrencies()
    {
        $namesCurrencies = array_keys($this->currencies);
        echo '[' . implode(', ', $namesCurrencies) . ']';
    }

    public function addCurrency(Currency $currency)
    {
        $this->currencies[$currency->getName()] = $currency;
    }

    public function getCurrency($nameCurrency)
    {
        return $this->currencies[$nameCurrency];
    }

    public function setMainCurrency(string $nameCurrency)
    {
        $this->mainCurrency = $nameCurrency;
    }

    public function getMainCurrency()
    {
        return $this->mainCurrency;
    }

    public function disableCurrency(string $nameCurrency)
    {
        // TODO: Implement disableCurrency() method.
    }

    public function getBalance($nameCurrency = null)
    {
        $currency = null;

        if ($nameCurrency) {
            $currency = $this->currencies[$nameCurrency];
        } else {
            $nameCurrency = $this->getMainCurrency();
            $currency = $this->currencies[$nameCurrency];
        }

        if (!$currency ||
            !$currency instanceof Currency) {
            throw new \Exception();
        }

        return $currency->getBalance() . " " . $nameCurrency;
    }

    public function transferToBalance(Currency $currency)
    {
        $existCurrency = $this->getCurrency($currency->getName());

        if (!$existCurrency) {
            throw new \Exception();
        }

        $currency->topUpYourBalance();
    }

    public function deductFromBalance(Currency $currency)
    {
        if (!$this->getCurrency($currency->getName())) {
            throw new \Exception();
        }

        $currency->deductFromBalance();

        return $currency;
    }
}