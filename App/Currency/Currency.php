<?php

namespace App\Currency;

use App\Traits\Exchanger;

/**
 * Абстрактный класс выбран потому как у нас могут быть еще допустим криптовалюты со своими особенностями реализации
 */
abstract class Currency
{
    use Exchanger;

    const STATUS_OFF = 'off';
    const STATUS_ON = 'on';

    protected $status;

    /**
     * Название валюты
     * @var
     */
    protected $name;

    /**
     * Баланс
     * @var int
     */
    protected $balance = 0;

    /**
     * Курсы обмена.
     * TODO На самом деле, кривовато их здесь хранить, надо отдельную сущность под это делать, потому что нарушается Single Responsibility. Обменник должен быть отдельно
     * @var array
     */
    protected $exchangeRate = [];

    /**
     * Здесь храним сумму транзакции
     * @var null
     */
    protected $preparedForTransaction = null;


    public function __construct($name)
    {
        $this->name = $name;
        $this->status = self::STATUS_ON;
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

    /**
     * Списываем с баланса
     * @return null
     * @throws \Exception
     */
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

    /**
     * Получить название валюты
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Сетим сумму для транзакции
     * @param $amount
     * @return $this
     */
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

    /**
     * Отключаем валюту. Перед отключением нужно проверять, не осталось ли на балансе денежных средств. Запрещать отключать если есть деньги на балансе
     */
    public function disable()
    {
        $this->status = self::STATUS_OFF;
    }

    /**
     * Получаем статус
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
