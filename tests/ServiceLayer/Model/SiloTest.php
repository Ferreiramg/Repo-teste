<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of SiloTest
 *
 * @author Luis Paulo
 */
class SiloTest extends PHPUnit {

    protected function setUp() {
        DBConnSqliteTest::ConnPDO();
    }

    protected function install() {
        $out = [];
        $data = new \Model\ProdutorReport();
        foreach (new Model\Produtor as $key => $value) {
            $out[] = $data->resumeInfoEntradas($value['id'], 1)['agregado'];
        }
        return $out;
    }

    public function testTotalSilo() {
        $silo = new \Model\Silo();
        $res = $silo->totalEstocado();
        $this->assertEquals((int)$res['ts'],0);
        $res2 = $silo->totalEstocado('2016');
        $this->assertEquals($res2['corrigido'], 0);
    }

}
