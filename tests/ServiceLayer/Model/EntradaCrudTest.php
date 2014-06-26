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
        DBConnSqliteTest::ConnPROPEL();
        $this->model = new \Model\Entrada();
    }

    private function post() {
        return [
            'produtor' => 1,
            'tipo' => 1,
            'peso' => 30600,
            'umidade' => '14.60',
            'impureza' => 1,
            'motorista' => "Luis",
            'ticket' => 00234,
            'observacao' => "",
            'data' => "10-06-2014",
            'acao' => 'create'
        ];
    }

    public function testDoInsert() {
        $this->model->csvfile = realpath('../') . \Configs::getInstance()->app->csv;
        $rows = $this->model->create($this->post());

        $this->assertEquals($rows, 1);
        $this->assertEquals($this->model->getUmidade(), 14.6);
        $data = $this->post();
        $data['tipo'] = 0;
        $rows2 = $this->model->create($data);
        $this->assertEquals($rows2, 1);
    }

    public function testDoDelete() {
        $de = $this->model->deletar(['id' => 2]);
        $this->assertTrue($de);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testExceptionConfiFileNotFound() {
        $this->model->create($this->post());
    }

    /**
     * @expectedException Exceptions\ClientExceptionResponse 
     */
    public function testExceptionDelete() {
        $this->model->deletar(['id' => 50]);
    }

}
