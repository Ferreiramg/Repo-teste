<?php

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

    protected function tearDown() {
        \Model\Cached\Memory::getInstance()->meminstance->delete('calendar:1');
        \Model\Cached\Memory::getInstance()->meminstance->delete('entrada:1');
    }

    public function testMainInitRequest() {
        ob_start();
        $main = new Main();
        \Model\Cached\Memory::getInstance()->meminstance->delete('calendar:1'); //hack for coverage
        Main::$EXTRA_PARAMS = array('calendar', 1);
        $main->run('GET', 'entrada_read');
        $this->assertTrue(strlen(ob_get_contents()) > 4900); //characters response
        ob_end_clean();
    }

    /**
     * @expectedException Exceptions\ClientExceptionResponse 
     */
    public function testCrudUsageDeleteException() {
        $main = new Main();
        Main::$EXTRA_PARAMS = array('deletar', 300);
        $main->run('DELETE', 'entrada');
    }

    public function testCrudUsageDeleteSuccess() {
        $main = new Main();
        Main::$EXTRA_PARAMS = array('deletar', 2);
        $this->expectOutputString('[{"code":"1"}]');
        $main->run('DELETE', 'entrada');
    }
    
    public function testRequestGetData(){
        $main = new Main();
        \Model\Cached\Memory::getInstance()->meminstance->delete('entrada:1');
        Main::$EXTRA_PARAMS = array(1);
        $this->expectOutputString('[{"id":"1","peso":"13000.0","saida_peso":"0.0","peso_corrigido":"11930.0","_cliente":"1","quebra_peso":null,"servicos":null,"desc_impureza":null,"umidade":"16.0","impureza":"1.0","ano":"2014","foi_transf":"0","data":"06\/05\/2014","ticket":"","observacao":" ;","group":"Entradas"}]');
        $main->run('GET', 'entrada_read');
    }

    public function testMakeRequestTest() {
        $this->markTestIncomplete(
                'Apache rewrite not work in travis.'
        );
        $response = $this->requestServer->get('http://localhost/entrada_read/calendar/1');
        $this->assertEquals($response->getStatusCode(), '200');
    }

    public function testCreateEntradaRequest() {
        $this->markTestIncomplete(
                'Apache rewrite not work in travis.'
        );
        $response = $this->requestServer->post('http://localhost/entrada', [
            'body' => [
                'produtor' => 1,
                'tipo' => 1,
                'peso' => 30600,
                'umidade' => '20.60',
                'impureza' => 1,
                'motorista' => "Luis",
                'ticket' => '00234',
                'observacao' => "",
                'data' => "10-06-2014",
                'acao' => 'create'
        ]]);
        $this->assertEquals($response->getStatusCode(), '200');
        echo $response;
    }

}
