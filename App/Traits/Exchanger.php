<?php

namespace App\Traits;

trait Exchanger
{
    /**
     * Конвертировать рубли в евро. На самом деле, можно написать более абстрактные функции
     * @param $courseExchange
     */
    public function convertRubToEur($courseExchange)
    {
        $_convertSum = $this->preparedForTransaction;
        $this->preparedForTransaction = round($_convertSum / $courseExchange, 2);
    }

    public function convertEurToRub($courseExchange)
    {
        $_convertSum = $this->preparedForTransaction;
        $this->preparedForTransaction = round($_convertSum * $courseExchange, 2);
    }

    public function convertUsdToRub($courseExchange)
    {
        $_convertSum = $this->preparedForTransaction;
        $this->preparedForTransaction = round($_convertSum * $courseExchange, 2);
    }

    /**
     * TODO здесь реализовываем функции конвертирования зачисляемой валюты ....
     */
}