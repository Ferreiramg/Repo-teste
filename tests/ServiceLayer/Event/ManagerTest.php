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
class ManagerTest extends PHPUnit {

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

    public function testInstanceOf() {
        $manager = new Event\ObsManager();
        $manager->attach(new testClient);

        $this->assertInstanceOf('testClient', $manager->dispatch());
    }

    public function testNotifyFunction() {
        $manager = new Event\ObsManager();
        $manager->attach(new testClient);
        $this->expectOutputString('First Test!');
        $manager->notify();
    }

    public function testDetachAndAttachMethods() {

        $manager = new Event\ObsManager();
        $manager->attach($cli = new testClient);
        $manager->detach($cli);
        $this->expectOutputString('');
        $manager->notify();
    }

}

class testClient implements \Client\ClientInterface {

    public function clientDelegate(\Client\Delegate $delegate) {
        
    }

    public function execute() {
        echo 'First Test!';
    }

    public function hasRequest() {
        
    }

    public function update(\Event\InterfaceEvent $subject) {
        $subject->dispatch()->execute();
    }

}
