<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;

include 'ConnectionTest.php';

/**
 * Description of InitConnTest
 *
 * @author Administrador
 */
class InitConnTest extends PHPUnit {

    public function testPropelMakeConnection() {
        $conn = ConnectionTest::initConn();

        $this->assertInstanceOf('\Propel\Runtime\Connection\ConnectionWrapper', $conn);
    }

}
