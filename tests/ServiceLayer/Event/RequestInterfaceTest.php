<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

/**
 * Description of RequestInterfaceTest
 *
 * @author Administrador
 */
class RequestInterfaceTest extends PHPUnit {

    protected $obj;

    protected function setUp() {
        $observer = new Event\ObsManager();
        $this->obj = $this->getMock(
                'Event\AbstractEvent', ['has', 'listen'], [new Client\Delegate($observer)]);
    }

    public function testClientInstanceOfAccess() {
        $this->assertInstanceOf('Client\Delegate', $this->obj->client);
    }

    public function testListenEvent() {
        $this->obj->expects($this->once())->method('has')->will($this->returnValue(true));
        $delgate = new \Event\Delegate();
        $delgate->addEvent($this->obj);
        $delgate->runEvents();
    }

}
