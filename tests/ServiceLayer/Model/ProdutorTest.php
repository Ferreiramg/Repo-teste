<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of ProdutorTest
 *
 * @author Luis Paulo
 */
class ProdutorTest extends PHPUnit {

    protected $prod;

    protected function setUp() {
        DBConnSqliteTest::ConnPDO(); //init connection sqlite db
        $this->prod = new \Model\Produtor();
    }

    public function testProdutorIteratorData() {
        $this->assertInstanceOf('\Iterator', $this->prod);
    }

    public function testProdutorIteratorAccessData() {
        $this->assertEquals($this->prod->offsetGet(0)['nome'], 'Luis Paulo');
        $this->assertEquals($this->prod->nome, 'Luis Paulo');
        $this->prod->setIdKey(1);
        $this->assertEquals($this->prod->nome, 'Ferreira');
        $this->assertEquals($this->prod->count(), 2);
        //$this->assertEquals($produtor->getTaxa(),0.43);
    }

    public function testInsertData() {
        $stmt = null;
        $rows = $this->prod->create($this->postArgs(), $stmt);
        $this->assertTrue($rows);
        $produtor2 = new \Model\Produtor(2);
        $this->assertEquals($produtor2->nome, 'Toninho');
    }

    public function testGetSaldoProdutor() {
        $this->prod->setIdKey(0);
        $this->assertEquals($this->prod->getSaldo(), 23860.0);
    }

    public function testUpdateData() {
        $stmt = null;
        $rows = $this->prod->update([
            'id' => 3,
            'nome' => 'Toninho Branquinho',
            'milho' => '',
            'data' => '03-07-2014'
                ], $stmt);
        $this->assertTrue($rows);
        $produtor2 = new \Model\Produtor(2);
        $this->assertEquals($produtor2->nome, 'Toninho Branquinho');
        $this->assertEquals($produtor2->grao, 'milho');
        $this->assertEquals($produtor2->armazenagem, '0.045');
    }

    public function testMissingArguments() {
        $stmt = null;
        $rows = $this->prod->create(['data' => ''], $stmt);
        $this->assertFalse($rows);
    }

    /**
     * @depends testInsertData
     */
    public function testDeleteData() {
        $rows = $this->prod->deletar(['id' => 3]);
        $this->assertEquals($rows, 1);
    }

    /**
     * @depends testDeleteData
     */
    public function testOffSetNoExist() {
        try {
            new \Model\Produtor(2);
        } catch (Exceptions\ClientExceptionResponse $e) {
            echo $e->renderJsonMessage(); //expected exception here
        }
        $this->expectOutputString('{"message":"Produtor Offset not Exists!","code":0,"severity":"error"}');
    }

    public function testMultipleInsertData() {
        $stmt = null;
        for ($i = 0; $i < 10; ++$i) {
            $rows = $this->prod->create($this->postArgs(), $stmt);
        }
        $this->assertTrue($rows);
        $produtor2 = new \Model\Produtor(11);
        $this->assertEquals($produtor2->id, '13');
    }

    public function testDeleteReOrderData() {
        $rows = $this->prod->deletar(['id' => 6]);
        $this->assertEquals($rows, 1);
        $produtor2 = new \Model\Produtor(10);
        $this->assertEquals($produtor2->id, '11');
    }

    private function postArgs() {
        return [
            'nome' => 'Toninho',
            'grao' => 'milho',
            'taxa' => 0.045,
            'data' => '03-07-2014'
        ];
    }

}
