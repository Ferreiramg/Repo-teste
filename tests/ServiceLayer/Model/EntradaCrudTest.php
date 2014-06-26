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
class EntradaCrudTest {

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

}
