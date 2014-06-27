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
        $this->model->csvfile = realpath('../') . \Configs::getInstance()->app->csv;
        $rows = $this->model->create($data = $this->post(), $stmt); //By reference debug mode

        $EXPECTED = "INSERT INTO `entradas` 
            (`peso`, `saida_peso`, `peso_corrigido`, `_cliente`, `umidade`, `impureza`, `data`, `ticket`, `observacao`)
                VALUES ('30600', '0', '28705.86', '1', '14.60', '1', '2014-06-25', '234', 'Luis: ')";

        $this->assertTrue($rows);
        $this->assertEquals("" . $EXPECTED . "", $stmt->getSQL());
        //Insert saida tipo =0;
        $data['tipo'] = 0;
        $rows = $this->model->create($data, $stmt);
        $EXPECTED = "INSERT INTO `entradas` 
            (`peso`, `saida_peso`, `peso_corrigido`, `_cliente`, `umidade`, `impureza`, `data`, `ticket`, `observacao`)
                VALUES ('0', '30600', '0', '1', '0', '0', '2014-06-25', '234', 'Luis: ')";

        $this->assertTrue($rows);
        $this->assertEquals("" . $EXPECTED . "", $stmt->getSQL());
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

}
