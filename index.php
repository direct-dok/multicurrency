<?php

use App\AppTest;

require_once('vendor/autoload.php');

$app_test = new AppTest();
echo $app_test->get_name();
