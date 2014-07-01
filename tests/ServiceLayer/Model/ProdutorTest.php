<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of ProdutorTest
 *
 * @author Luis Paulo
 */
class ProdutorTest extends PHPUnit {

    protected function setUp() {
        DBConnSqliteTest::ConnPDO(); //init connection sqlite db
    }

    public function testProdutorIteratorData() {
        $produtor = new \Model\Produtor();
        $this->assertInstanceOf('\Iterator', $produtor);
    }

    public function testProdutorIteratorAccessData() {
        $produtor = new \Model\Produtor();
        $this->assertEquals($produtor->offsetGet(0)['nome'], 'Luis Paulo');
        $this->assertEquals($produtor->nome, 'Luis Paulo');
        $produtor->setIdKey(1);
        $this->assertEquals($produtor->nome, 'Ferreira');
        $this->assertEquals($produtor->count(), 2);
        //$this->assertEquals($produtor->getTaxa(),0.43);
    }

}
