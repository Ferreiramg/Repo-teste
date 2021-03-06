<?php

use \PHPUnit_Framework_TestCase as PHPUnit;
use GuzzleHttp\Client;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of ProdutorReadTest
 *
 * @author Luís Paulo
 */
class ProdutorReadTest extends PHPUnit {

    protected function setUp() {
        DBConnSqliteTest::ConnPDO(); //init connection sqlite db
    }

    public function testReadDataProdutor() {
        $main = new Main();
        \Model\Cached\Memory::getInstance()->meminstance->delete('produtor:'); //hack for coverage
        $this->expectOutputString($this->expected);
        $main->run('GET', 'produtor_read');
    }

    public function testReadReportDataProdutor() {
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
        $main = new Main();
        Main::$EXTRA_PARAMS = [1];
        $main->run('GET', 'produtor_report');
    }

    public function testInsertDataProdutor() {
        $request = new Client();
        $this->markTestIncomplete(
                'Apache rewrite not work in travis ci.'
        );
        $response = $request->post('http://localhost/produtor', [
            'body' => [
                'nome' => 'Antonio Rezende',
                'armazenagem' => 0.033, //SQL REAL Type
                'grao' => 'Soja',
                'data' => "10-06-2014",
                'acao' => 'create'
        ]]);
        echo $response->getBody();
        $this->expectOutputString('[{"code":"1"}]');
    }

    public function testUpdateDataProdutor() {
        $request = new Client();
        $this->markTestIncomplete(
                'Apache  rewrite not work in travis ci.'
        );
        $response = $request->post('http://localhost/produtor', [
            'body' => [
                'id' => '18',
                'email' => 'antonio@hotmail.com',
                'acao' => 'update'
        ]]);
        echo $response->getBody();
        $this->expectOutputString('[{"code":"1"}]');
    }

    public function testDeleteDataProdutor() {
        $main = new Main();
        Main::$EXTRA_PARAMS = array('deletar', 2);
        $this->expectOutputString('[{"code":"1"}]');
        $main->run('DELETE', 'produtor');
    }

    /**
     * @expectedException Exceptions\ClientExceptionResponse 
     */
    public function testExceptionDeleteDataProdutor() {
        $main = new Main();
        Main::$EXTRA_PARAMS = array('deletar', 450); //error id
        $main->run('DELETE', 'produtor');
    }

    private $expected = '[{"id":"1","nome":"Luis Paulo","email":null,"grao":"Milho","data":"2014-05-28 16:52:29","armazenagem":"0.033"},{"id":"2","nome":"Ferreira","email":null,"grao":"Milho","data":"2014-06-28 16:52:29","armazenagem":"0.043"}]';

}
