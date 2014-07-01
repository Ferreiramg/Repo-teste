<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of ProdutorReadTest
 *
 * @author Laticinios PJ
 */
class ProdutorReadTest extends PHPUnit {

    protected function setUp() {
        DBConnSqliteTest::ConnPDO(); //init connection sqlite db
    }

    public function testReadDataProdutor() {
        $main = new Main();
        $this->expectOutputString($this->expected);
        $main->run('GET', 'produtor_read');
    }

    private $expected = '[{"id":"1","nome":"Luis Paulo","grao":"Milho","data":"2014-05-28 16:52:29","armazenagem":"0.033"},{"id":"2","nome":"Ferreira","grao":"Milho","data":"2014-06-28 16:52:29","armazenagem":"0.043"}]';

}
