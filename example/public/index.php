<?php

require "../../vendor/autoload.php";

use Tebe\Pvc\Application;

try {

    $config = require "../config/main.php";
    Application::instance($config)->run();

} catch (Throwable $t) {

    echo $t->getMessage() . '<br>';
    echo nl2br($t->getTraceAsString());

}
