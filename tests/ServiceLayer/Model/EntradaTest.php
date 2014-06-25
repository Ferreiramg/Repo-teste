<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of EntradaTest
 *
 * @author Administrador
 */
class EntradaTest extends PHPUnit {

    protected $model;

    protected function setUp() {
        DBConnSqliteTest::ConnPROPEL();
        $this->model = new Model\Entrada();
    }

    public function testInstanceOf() {

        foreach (EntradasQuery::create()->find() as $values) {
            var_dump($values);
        }
    }

}
