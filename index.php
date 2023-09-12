<?php

require_once('vendor/autoload.php');

use App\Bank\Bank;
use App\Currency\BankCurrency as Currency;

$bank = new Bank();
$account = $bank->openNewAccount('Businnes Account');

$rub = new Currency(Currency::RUB);
$usd = new Currency(Currency::USD);
$eur = new Currency(Currency::EUR);

$account->addCurrency($rub);
$account->addCurrency($usd);
$account->addCurrency($eur);

$account->setMainCurrency(Currency::RUB);

$account->listSupportedCurrencies();

$account->transferToBalance($rub(1000));
$account->transferToBalance($eur(50));
$account->transferToBalance($usd(150));
//
echo $account->getBalance() . "<br>";
echo $account->getBalance(Currency::EUR) . "<br>";
echo $account->getBalance(Currency::USD) . "<br>";
//
$account->transferToBalance($rub(1000));
$account->transferToBalance($eur(50));
$account->deductFromBalance($usd(10));
//
$eur->setExchangeRate(Currency::RUB, 150);
$usd->setExchangeRate(Currency::RUB, 100);
//
echo $account->getBalance() . "<br>";
//
$account->setMainCurrency(Currency::EUR);
//
echo $account->getBalance() . "<br>";

$cash = $account->deductFromBalance($rub(1000));
//
//$account->transferToBalance($eur($cash));

echo "<pre>";
var_dump($account);
echo "</pre>";