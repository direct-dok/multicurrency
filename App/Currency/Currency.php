<?php

namespace App\Currency;

abstract class Currency
{
    protected $name;
    protected $balance = 0;
    protected $exchangeRate = [];
    protected $preparedForTransaction = null;
    protected $writeOffTransactionAmount = null;

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

    public function topUpYourBalance()
    {
        $this->balance += $this->preparedForTransaction;
        $this->preparedForTransaction = null;
    }

    public function deductFromBalance()
    {
        if ($this->preparedForTransaction > $this->balance) {
            throw new \Exception();
        }

        $copyBalance = $this->balance;
        $this->balance = $copyBalance - $this->preparedForTransaction;
        $this->preparedForTransaction = null;
    }

    public function writeOffTransaction($amount)
    {
        if ($amount > $this->balance) {
            throw new \Exception();
        }

        $this->writeOffTransactionAmount = $amount;
        return $this;
    }

    public function commitOffTransaction()
    {
        $this->deductFromBalance($this->writeOffTransactionAmount);
        $amountDebited = $this->writeOffTransactionAmount;
        $this->writeOffTransactionAmount = null;
        return $amountDebited;
    }

    public function getName()
    {
        return $this->name;
    }

    public function __invoke($amount)
    {
        $this->preparedForTransaction = $amount;
        return $this;
    }
}
