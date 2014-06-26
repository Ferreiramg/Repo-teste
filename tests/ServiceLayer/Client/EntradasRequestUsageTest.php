<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;
use GuzzleHttp\Client;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of EntradasRequestUsageTest
 *
 * @author Luis Paulo
 */
class EntradasRequestUsageTest extends PHPUnit {

    protected $requestServer;

    protected function setUp() {
        $this->requestServer = new Client();
        DBConnSqliteTest::ConnPROPEL();
    }

    public function testMakeRequestTest() {
        $response = $this->requestServer->get('http://127.0.0.1/');
        $this->assertEquals($response->getStatusCode(), '200');
    }

    public function testCreateEntradaRequest() {
       $response = $this->requestServer->post('http://127.0.0.1/entrada', [
            'body' => [
                'produtor' => 1,
                'tipo' => 1,
                'peso' => 30600,
                'umidade' => '14.60',
                'impureza' => 1,
                'motorista' => "Luis",
                'ticket' => 00234,
                'observacao' => "",
                'data' => "10-06-2014",
                'acao' => 'create'
    ]]);
      $this->assertEquals($response->getStatusCode(), '200'); 
    }

}
