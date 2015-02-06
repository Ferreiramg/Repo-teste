<?php

require_once 'vendor/' . 'autoload.php';

ini_set('display_errors', 1);
ini_set('memory_limit', '32M');

error_reporting(E_ALL | E_STRICT);

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
header('Cache-Control: no-cache');
session_start();

Configs::getInstance()->set('connection.mysql.pass', 'saga123');

define('ROOT', dirname(__FILE__));
define('CACHE_TIME', 3600);
try {
    $main = new Main();
    $main->run($_SERVER['REQUEST_METHOD']);
} catch (\Exceptions\ClientExceptionResponse $response) {
    echo $response->renderJsonMessage();
} catch (\Exception $e) {
    echo json_encode([['message' => $e->getMessage(), 'code' => "0"]]);
}