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
        $out = array('amz' => 0, 'qp' => 0);
        $data = new \Model\ProdutorReport();
        foreach (new Model\Produtor as $value) {
            $out['amz'] += $data->resumeInfoEntradas($value['id'], 1)['agregado']['armazenagem'];
            $out['qp'] += $data->resumeInfoEntradas($value['id'], 1)['agregado']['qp'];
        }
        return $out;
    }

    public function testSilogetAmzSaldos() {
        var_dump($this->install());
    }

    public function testTotalSilo() {
        $silo = new \Model\Silo();
        $res = $silo->totalEstocado();
        $this->assertEquals((int) $res['ts'], 0);
        $res2 = $silo->totalEstocado('2016');
        $this->assertEquals($res2['corrigido'], 0);
    }

}
