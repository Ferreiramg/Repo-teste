<?php

$autoload = require_once 'vendor/' . 'autoload.php';
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('America/Sao_Paulo');
$main = new Main();

//$main->run($_SERVER['REQUEST_METHOD'], header_sent('X-Silo'));

function header_sent($header) {
    $headers = headers_list();
    $header = trim($header, ': ');
    foreach ($headers as $hdr) {
        if (stripos($hdr, $header) !== false) {
            return explode(':', $hdr);
        }
    }
    return null;
}
