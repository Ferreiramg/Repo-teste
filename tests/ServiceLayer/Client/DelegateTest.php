<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

/**
 * Description of DelegateTest
 *
 * @author Administrador
 */
class DelegateTest extends PHPUnit {

    /**
     * @expectedException RuntimeException
     */
    public function testClientNotFoundException() {
        $mock = $this->getMock('Client\AbstracClient', ['hasRequest', 'execute']);
        $mock->expects($this->any())
                ->method('hasRequest')
                ->will($this->returnValue(false));

        $mock2 = $this->getMock('Event\ObsManager');
        $mock2->expects($this->any())
                ->method('attach');

        $delegate = new Client\Delegate($mock2);
        $delegate->addClient($mock);
        $delegate->runClient();
    }

}
