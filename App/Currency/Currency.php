<?php

namespace App\Currency;

use App\Traits\Exchanger;

abstract class Currency
{
    use Exchanger;

    protected $name;
    protected $balance = 0;
    protected $exchangeRate = [];
    protected $preparedForTransaction = null;
    protected $writeOffTransactionAmount = null;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Установить курс обмена валют
     * @param string $nameCurrency
     * @param int $course
     * @throws \Exception
     */
    public function setExchangeRate(string $nameCurrency, int $course)
    {
        if ($nameCurrency == $this->name) {
            throw new \Exception();
        }

        $this->exchangeRate[$nameCurrency] = $course;
    }

    /**
     * Получить баланс
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Пополнение баланса
     */
    public function topUpYourBalance()
    {
        $_depositAmount = $this->preparedForTransaction;
        $this->balance += $this->preparedForTransaction;
        $this->preparedForTransaction = null;

        return $_depositAmount;
    }

    public function deductFromBalance()
    {
        if ($this->preparedForTransaction > $this->balance) {
            throw new \Exception();
        }

        $_amountDebited = $this->preparedForTransaction;
        $copyBalance = $this->balance;
        $this->balance = $copyBalance - $this->preparedForTransaction;
        $this->preparedForTransaction = null;

        return $_amountDebited;
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

    /**
     * @param $currencyEnrollment
     * @throws \Exception
     */
    public function convertEnrollment($currencyEnrollment)
    {
        $courseExchange = $this->exchangeRate[$currencyEnrollment];

        if (!$courseExchange) {
            throw new \Exception();
        }

        $nameConvertFunc = $this->getNameFuncConvert($currencyEnrollment, $this->getName());

        if (method_exists($this, $nameConvertFunc)) {
            $this->{$nameConvertFunc}($courseExchange);
        }
    }

    /**
     * Преобразует строки в имя метода конвертера
     * @param $from
     * @param $where
     */
    private function getNameFuncConvert($from, $where)
    {
        return 'convert' . ucfirst($from) . 'To' . ucfirst($where);
    }


    // Неиспользуемые методы
    // Неиспользуемые методы

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
}
