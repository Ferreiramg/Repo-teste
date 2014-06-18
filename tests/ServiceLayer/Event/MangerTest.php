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
class MangerTest extends PHPUnit {

    protected $manager;

    protected function setUp() {
        $this->manager = $this->getMock('Event\Manager');
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

    public function testExecutebyNotify() {
        $manager = new \Event\Manager();
        $manager->attach(new testClient());
        $manager->attach(new testClient2());
        $this->expectOutputString('Fist test Init. /n Second test!');
        $manager->notify();
    }

}

class testClient implements \Client\ExecuteInterface {

    public function execute() {
        echo "Fist test Init. /n";
    }

    public function update(\Client\ExecuteInterface $subject) {
        $subject->execute();
    }

}

class testClient2 implements \Client\ExecuteInterface {

    public function execute() {
        echo " Second test!";
    }

    public function update(\Client\ExecuteInterface $subject) {
        $subject->execute();
    }

}
