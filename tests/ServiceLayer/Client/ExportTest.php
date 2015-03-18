<?php

use \PHPUnit_Framework_TestCase as PHPUnit,
    \Model\Cached\Memory;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of ExportTest
 *
 * @author Luis Paulo
 */
class ExportTest extends PHPUnit {

    protected $export;

    protected function setUp() {
        \DBConnSqliteTest::ConnPDO();
        Memory::getInstance()->delete();
        $json_cached = json_encode([[
                    "dia" => "20-02-2015",
                    'entrada' => 0,
                    'saida' => 0,
                    'desconto' => 0,
                    'saldo' => 20,
                    'observacao' => 'testes unit']]
                );
        $key = \Client\EntradaRead::C_KEY . '2' . \Model\Silo::getSessionYear();
        Memory::getInstance()->meminstance->set($key,  $json_cached);
    }

    /**
     * @expectedException Exceptions\ClientExceptionResponse
     * @expectedExceptionMessage Não existe data no cache!
     */
    public function testDataEmptyException() {
        $main = new Main();
        Main::$EXTRA_PARAMS = array(1);
        $main->run('GET', 'export');
    }

    public function testExportSuccess() {
        $main = new Main();
        Main::$EXTRA_PARAMS = array(2);
        $main->run('GET', 'export');
    }

}
