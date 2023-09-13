<?php

namespace App\Bank;

use App\Bank\BankInterface;
use App\Currency\Currency;

class Bank implements BankInterface
{
    /**
     * Тип операции списание
     */
    const OPERATION_WRITE_OFF = 'write_off';

    /**
     * Тип операции зачисление
     */
    const OPERATION_ENROLLMENT = 'enrollment';

    /**
     * Название аккаунта
     * @var
     */
    private $accountName;

    /**
     * Массив объектов валют
     * @var array
     */
    private $currencies = [];

    /**
     * Название главной валюты
     * @var
     */
    protected $mainCurrency;

    /**
     * Лог транзакций
     * [
     *  ["type" => тип операции, "currency" => "RUB or USD or EUR", "amount" => "сумма операции"]
     * ]
     * @var array
     */
    protected $transactionLog = [];

    /**
     * Создать новый аккаунт. Если честно не понял зачем через метод создавать, наверное есть какой то смысл, но по идее можно все в конструкторе создать
     * @param $name
     * @return $this
     */
    public function openNewAccount($name)
    {
        $this->accountName = $name;
        return $this;
    }

    /**
     * Получить список поддерживаемых валют
     * @return string
     */
    public function listSupportedCurrencies()
    {
        $_supportedCurrencies = [];

        foreach ($this->currencies as $currency) {
            if ($currency->getStatus() == Currency::STATUS_ON) {
                $_supportedCurrencies[] = $currency->getName();
            }
        }
        return '[' . implode(', ', $_supportedCurrencies) . '] <br>';
    }

    /**
     * Добавляем валюту
     * @param Currency $currency
     */
    public function addCurrency(Currency $currency)
    {
        $this->currencies[$currency->getName()] = $currency;
    }

    /**
     * Возвращает объект валюты
     * @param $nameCurrency
     * @return Currency
     */
    public function getCurrency($nameCurrency) : Currency
    {
        return $this->currencies[$nameCurrency];
    }

    /**
     * Устаовить основную валюту счета
     * @param string $nameCurrency
     */
    public function setMainCurrency(string $nameCurrency)
    {
        $this->mainCurrency = $nameCurrency;
    }

    /**
     * Получить название основной валюты счета
     * @return mixed
     */
    public function getMainCurrency()
    {
        return $this->mainCurrency;
    }

    /**
     * Отключить валюту и сконвертировать остаток по счету в основную валюту счета, с зачислением на баланс
     * @param string $nameCurrency
     */
    public function disableCurrency(string $nameCurrency)
    {
        $_disableCurrency = $this->getCurrency($nameCurrency);
        $_mainCurrencyAccount = $this->getCurrency($this->getMainCurrency());

        $_disableCurrencyBalance = $_disableCurrency->getBalance();

        $cash = $this->deductFromBalance($_disableCurrency($_disableCurrencyBalance));
        $this->transferToBalance($_mainCurrencyAccount($cash));

        $_disableCurrency->disable();
    }

    /**
     * Получить баланс
     * @param null $nameCurrency
     * @return string
     * @throws \Exception
     */
    public function getBalance($nameCurrency = null)
    {
        $_currency = null;

        if ($nameCurrency) {
            $_currency = $this->currencies[$nameCurrency];
        } else {
            $nameCurrency = $this->getMainCurrency();
            $_currency = $this->currencies[$nameCurrency];
        }

        if (!$_currency ||
            !$_currency instanceof Currency) {
            throw new \Exception();
        }

        return $_currency->getBalance() . " " . $nameCurrency;
    }

    /**
     * Пополнить баланс
     * @param Currency $currency
     * @throws \Exception
     */
    public function transferToBalance(Currency $currency)
    {
        $_сurrency = $this->getCurrency($currency->getName());

        $_lastTransaction = $this->getLastTransactionFromLog();

        if ($_lastTransaction &&
            $_lastTransaction['type'] == self::OPERATION_WRITE_OFF &&
            $_lastTransaction['currency'] != $_сurrency->getName()) {
            $_сurrency->convertEnrollment($_lastTransaction['currency']);
        }

        if (!$_сurrency) {
            throw new \Exception();
        }

        $_depositAmount = $_сurrency->topUpYourBalance();
        $this->writeTransactionLog(self::OPERATION_ENROLLMENT, $currency->getName(), $_depositAmount);
        return $_depositAmount;
    }

    /**
     * Списать с баланса
     * @param Currency $currency
     * @return mixed
     * @throws \Exception
     */
    public function deductFromBalance(Currency $currency)
    {
        $_сurrency = $this->getCurrency($currency->getName());

        if (!$_сurrency) {
            throw new \Exception();
        }

        $_amountDebited = $_сurrency->deductFromBalance();
        $this->writeTransactionLog(self::OPERATION_WRITE_OFF, $currency->getName(), $_amountDebited);
        return $_amountDebited;
    }

    /**
     * Записываем транзакцию в лог
     * @param string $type
     * @param string $currency
     * @param $amount
     */
    private function writeTransactionLog(string $type, string $currency, $amount)
    {
        $_transaction = [
            'type' => $type,
            'currency' => $currency,
            'amount' => $amount
        ];

        $this->transactionLog[] = $_transaction;
    }

    /**
     * Получаем последнюю транзакцию
     * @return false|mixed
     */
    private function getLastTransactionFromLog()
    {
        return end($this->transactionLog);
    }
}