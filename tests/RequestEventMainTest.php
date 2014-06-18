<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;

/**
 * Description of RequestEventMainTest
 *
 * @author Laticinios PJ
 */
class RequestEventMainTest extends PHPUnit {

    public function testInitMain() {

        $main = new Main();
        $this->expectOutputString('WoW Delete');
        $main->run('DELETE', 'testClientController');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testEventRuntimeExceptionError() {
        $main = new Main();
        $main->run('READ', 'testClientController');
    }

    /**  
     * @expectedException RuntimeException
     */
    public function testClientErrorHandler() {
        $main = new Main();
        $main->run('PUT', 'notFoudClient');
    }

}

class testClientController extends Client\EventExecuteInterface {

    public function execute() {
        echo 'WoW Delete';
    }

}
