<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of SiloTest
 *
 * @author Luis Paulo
 */
class SiloTest extends PHPUnit {

    protected $silo;

    protected function setUp() {
        $this->silo = new \Model\Silo();
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
        $res = $this->silo->totalEstocado();
        $this->assertEquals((int) $res['ts'], 0);
        $res2 = $this->silo->totalEstocado('2016');
        $this->assertEquals($res2['corrigido'], 0);
    }

    public function testSimulator() {
        $data = $this->silo->simulador(['peso' => 250, 'taxa' => 0.045, 'dias' => 90]);
        $this->assertEquals($data['armz'], 10.13);
    }

    /**
     * Error \RuntimeException get in 'msg'
     * @see \Model\CalcDiscounts::getCsvDataFiltering
     * 
     */
    public function testSimulatorException() {
        $data = $this->silo->simulador(['peso' => 250,
            'taxa' => 0.045,
            'dias' => 90,
            'umidade' => 17.9, //wrong value
            'impureza' => 1
        ]);
        $this->assertEquals("17.9 , não é multiplo de dois", $data['msg']);
    }

}
