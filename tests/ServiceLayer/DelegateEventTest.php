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
                ->addEvent(new Event\Put)
                ->addEvent(new Event\Post)
                ->addEvent(new Event\Get);
    }

    public function testApplyClientObserver() {
        Main::$Action = 'ClientEventListternTest';
        Main::$REQUEST = 'DELETE';
        $event = new \Event\Manager();
        $this->delegateObject->runClient($event);
        $event->notify();
        $this->assertinstanceof('ClientEventListternTest', $ob = $this->delegateObject->getClient());
        $this->assertEquals($ob->event,'DELETE');
        Main::$REQUEST = 'PUT';
        $event->notify();
        $this->assertinstanceof('ClientEventListternTest', $ob = $this->delegateObject->getClient());
        $this->assertEquals($ob->event,'PUT');
        Main::$REQUEST = 'POST';
        $event->notify();
         $this->assertinstanceof('ClientEventListternTest', $ob = $this->delegateObject->getClient());
        $this->assertEquals($ob->event,'POST');
        Main::$REQUEST = 'GET';
        $event->notify();
        $this->assertinstanceof('ClientEventListternTest', $ob = $this->delegateObject->getClient());
        $this->assertEquals($ob->event,'GET');
    }

}

class ClientEventListternTest extends \Client\EventExecuteInterface {

    public $event;
    public function execute() {
        $this->event = Main::$REQUEST;
    }

}
