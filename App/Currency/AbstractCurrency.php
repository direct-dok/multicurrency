<?php

namespace App\Currency;

abstract class AbstractCurrency
{
    protected $name;
    protected $balance = 0;
    protected $exchangeRate = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function setExchangeRate(string $nameCurrency, int $course)
    {
        if ($nameCurrency == $this->name) {
            throw new \Exception();
        }

        $this->exchangeRate[$nameCurrency] = $course;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function topUpYourBalance($amount)
    {
        $this->balance += $amount;
    }

    public function deductFromBalance($amount)
    {
        if ($amount > $this->balance) {
            throw new \Exception();
        }

        $copyBalance = $this->balance;
        $this->balance = $copyBalance - $amount;
    }

    public function getName()
    {
        return $this->name;
    }
}
