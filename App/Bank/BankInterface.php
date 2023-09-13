<?php

namespace App\Bank;

use App\Account\AccountInterface;

/**
 * Интерфейс нам нужен для более гибкой  архитектуры, чтобы мы могли в клиентском коде при необходимости заменять реализации и у нас был полиморфизм и ничего не ломалось,
 * так как мы сможем абстрагироваться от конкретной реализации и работать основываясь на одном интерфейсе
 */
interface BankInterface
{
    public function openNewAccount($name);
    public function disableCurrency(string $nameCurrency);
}