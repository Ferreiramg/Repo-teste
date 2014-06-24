<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;

/**
 * Description of MangerTest
 *
 * @author Laticinios PJ
 */
class ManagerTest {

    protected $manager;

    protected function setUp() {
        $this->manager = $this->getMock('Event\ObsManager');
    }

    public function testAttachClient() {
        $this->manager->expects($this->any())
                ->method('attach');
        $this->manager->attach(new testClient());
    }

    public function testDetachClient() {
        $this->manager->expects($this->any())
                ->method('detach');
        $this->manager->detach(new testClient());
    }

}

class testClient  {

    public function execute() {
        echo "Fist test Init. /n";
    }

    public function update( $subject) {
        $subject->execute();
    }

}

class testClient2 {

    public function execute() {
        echo " Second test!";
    }

    public function update( $subject) {
        $subject->execute();
    }

}
