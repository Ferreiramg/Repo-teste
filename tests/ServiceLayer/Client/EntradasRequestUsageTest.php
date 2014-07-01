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
        DBConnSqliteTest::ConnPDO();
    }

    public function testMainInitRequest() {
        ob_start();
        $main = new Main();
        Main::$EXTRA_PARAMS = array('calendar', 1);
        $main->run('GET', 'entrada_read');
        $this->assertTrue(strlen(ob_get_contents()) > 4900); //characters response
        ob_end_clean();
    }

    /**
     *@expectedException Exceptions\ClientExceptionResponse 
     */
    public function testCrudUsageDeleteException() {
        $main = new Main();
        Main::$EXTRA_PARAMS = array('deletar', 300);
        $main->run('DELETE', 'entrada');
    }

    public function testMakeRequestTest() {
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
        $response = $this->requestServer->get('http://localhost/entrada_read/calendar/1');
        $this->assertEquals($response->getStatusCode(), '200');
    }

    public function testCreateEntradaRequest() {
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
        $response = $this->requestServer->post('http://localhost', [
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
