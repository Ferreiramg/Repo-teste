<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once 'DBConnSqliteTest.php';

/**
 * Description of InitConnTest
 *
 * @author Administrador
 */
class InitConnTest extends PHPUnit {

    public function testMakeConnection() {
        \Configs::getInstance()->set('debug', true);
        $con = Model\Connection\Init::getInstance()->on();

        $this->assertInstanceOf('\PDO', $con);
        $this->assertTrue($con === Model\Connection\Init::getInstance()->on());
    }

    public function testConnSqlite() {
        $conn = Model\Connection\Init::getInstance()->on();
        $query = $conn->query("SELECT * FROM cliente");
        var_dump($query->fetchAll(2));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetPdoException() {
        \Configs::getInstance()->set('debug', false);
        Model\Connection\Init::getInstance()->unsetConn();
        Model\Connection\Init::getInstance()->on();
    }

}
