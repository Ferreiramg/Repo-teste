<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;

/**
 * Description of DelegateEventTest
 *
 * @author Laticinios PJ
 */
class DelegateEventTest extends PHPUnit {

    protected $delegateObject;

    protected function setUp() {
        $this->delegateObject = new DelegateEvent();
        $this->delegateObject
                ->addEvent(new Event\Delete)
                ->addEvent(new Event\Put);
    }

    public function testApplyClientObserver() {
        Main::$Action = '\Client\Entrada';
        Main::$REQUEST = 'DELETE';
        $this->delegateObject->runClient(new \Event\Manager);
        $this->assertinstanceof('\Client\Entrada', $this->delegateObject->getClient());
    }

}
