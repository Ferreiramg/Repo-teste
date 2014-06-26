<?php

$autoload = require_once 'vendor/' . 'autoload.php';
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('America/Sao_Paulo');


var_dump($_SERVER['REQUEST_URI']);
var_dump($_SERVER['SCRIPT_NAME']);
var_dump(Main::$Action);
exit();
try {
    $main = new Main();
    $main->run($_SERVER['REQUEST_METHOD']);
} catch (\Exception\ClientExceptionResponse $response) {
    echo $response->renderJsonMessage();
} catch (\Exception $e) {
    echo $e->getMessage();
}



