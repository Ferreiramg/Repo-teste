<?php

$autoload = require_once 'vendor/' . 'autoload.php';

ini_set('display_errors', 1);
ini_set('memory_limit', '32M');

error_reporting(E_ALL | E_STRICT);

date_default_timezone_set('America/Sao_Paulo');

Configs::getInstance()->set('connection.mysql.pass', 'saga123');

try {
    $main = new Main();
    $main->run($_SERVER['REQUEST_METHOD']);
} catch (\Exception\ClientExceptionResponse $response) {
    echo $response->renderJsonMessage();
} catch (\Exception $e) {
    echo $e->getMessage();
}