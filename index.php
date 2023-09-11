<?php

require_once('vendor/autoload.php');

use App\Account\Account;
use App\Db\Db;

$db = Db::get_instance();

$result = $db->getRow('test', " where id = :id", ['id' => 1]);
var_dump($result);