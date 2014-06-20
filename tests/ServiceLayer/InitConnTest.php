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

    public function testPropelMakeConnection() {
        $conn = DBConnSqliteTest::ConnPROPEL();

        $this->assertInstanceOf('\Propel\Runtime\Connection\ConnectionWrapper', $conn);
    }

}
