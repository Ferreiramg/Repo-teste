<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of EntradaCrudTest
 *
 * @author Administrador
 */
class EntradaCrudTest extends PHPUnit {

    protected $model;

    protected function setUp() {
        $this->model = new \Model\Entrada();
        DBConnSqliteTest::ConnPDO();
    }

    private function post() {
        return [
            'produtor' => 1,
            'wastrans' => "0",
            'tipo' => 1,
            'peso' => 30600,
            'umidade' => '14.60',
            'impureza' => 1,
            'motorista' => "Luis",
            'ticket' => 234,
            'observacao' => "",
            'data' => "2014-06-25",
            'acao' => 'create'
        ];
    }

    public function testDoInsert() {
        $this->model->csvfile = ROOT . \Configs::getInstance()->app->csv;
        $rows = $this->model->create($data = $this->post(), $stmt); //By reference debug mode

        $EXPECTED = "INSERT INTO `entradas` (`peso`, `saida_peso`, `peso_corrigido`, `_cliente`, `umidade`, `impureza`, `data`, `ticket`, `observacao`,`quebra_peso`,`servicos`,`desc_impureza`,`foi_transf`) VALUES ('30600', '0', '28705.86', '1', '14.60', '1', '2014-06-25 00:00:00', '234', 'Luis: ','553.86','1034.28','306','0')";

        $this->assertEquals((int) $rows, 3);
        $this->assertEquals("" . $EXPECTED . "", $stmt->getSQL());
        //Insert saida tipo =0;
        $data['tipo'] = 0;
        $rows = $this->model->create($data, $stmt);
        $EXPECTED = "INSERT INTO `entradas` (`peso`, `saida_peso`, `peso_corrigido`, `_cliente`, `umidade`, `impureza`, `data`, `ticket`, `observacao`,`quebra_peso`,`servicos`,`desc_impureza`,`foi_transf`) VALUES ('0', '30600', '0', '1', '0', '0', '2014-06-25 00:00:00', '234', 'Luis: ','0','0','0','0')";
        $this->assertEquals((int) $rows, 4);
        $this->assertEquals("" . $EXPECTED . "", $stmt->getSQL());
    }

    public function testDoInsertQt() {
       $rows = $this->model->makeQT($this->post());
        $this->assertEquals((int) $rows, 5);
    }

    /**
     * Exception when a file can not be set for property $this->csvfile
     * @expectedException RuntimeException
     */
    public function testExceptionConfiFileNotFound() {
        $this->model->create($this->post());
    }

    /**
     * @depends testDoInsert
     */
    public function testdoDelete() {
        $rows = $this->model->deletar(['id' => 3]);
        $this->assertEquals($rows, 1);
        $rows = $this->model->deletar(['id' => 4]);
        $this->assertEquals($rows, 1);
    }

    public function testValidateDate() {
        $reflection = new \ReflectionClass('\Model\Entrada');
        $method = $reflection->getMethod('validateDate');
        $method->setAccessible(true);
        $data = '14-05-2014';
        $method->invokeArgs($this->model, array($data));

        ///Error teste
        $this->setExpectedException('\Exceptions\ClientExceptionResponse');
        $data = '14-052014'; //wrong
        $method->invokeArgs($this->model, array($data));
    }

    /**
     * @expectedException \Exceptions\ClientExceptionResponse
     */
    public function testValidateDateErrorDateFuture() {
        $reflection = new \ReflectionClass('\Model\Entrada');
        $method = $reflection->getMethod('validateDate');
        $method->setAccessible(true);
        $data = '14-05-2035';
        $method->invokeArgs($this->model, array($data));
    }

}
