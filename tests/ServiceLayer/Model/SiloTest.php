<?php

use \PHPUnit_Framework_TestCase as PHPUnit,
    Model\Connection;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of SiloTest
 *
 * @author Luis Paulo
 */
class SiloTest extends PHPUnit {

    protected $silo, $conn;

    protected function setUp() {
        $this->silo = new \Model\Silo();
        DBConnSqliteTest::ConnPDO();
    }

    public function testSilogetAmzSaldos() {
        //var_dump($this->install());
    }

    public function testGetAllProdutoresData() {
        $data = $this->silo->getAllDataProdutores();
        var_dump($data['saldo']);
    }

    public function testDeleteAmz() {

        $this->assertEquals(1, $this->silo->delete('12-05-2014')); //true
    }

    public function testChartArmz() {
        $data = $this->silo->armzChart();
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
