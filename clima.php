<?php

ini_set('display_errors', 1);
ini_set('memory_limit', '32M');

error_reporting(E_ALL | E_STRICT);

$xml = simplexml_load_file('http://developers.agenciaideias.com.br/tempo/xml/ingai-MG');

$json = json_decode(json_encode((array)$xml), TRUE);//xml to array.
echo json_encode($json);
