<?php

require_once('vendor/autoload.php');

use App\Account\Account;
use App\Currencies\AccountsCurrencies;

//$db = Db::get_instance();
//
//$result = $db->getRow('test', " where id = :id", ['id' => 1]);
//var_dump($result);

$acc = new Account();
var_dump($acc->create_account()->add_currency('RUB'));

$accounts_currencies = new AccountsCurrencies();
$accounts_currencies->set_currency(1, 2);