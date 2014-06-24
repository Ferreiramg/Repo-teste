<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;

/**
 * Description of MainInitTest
 *
 * @author Administrador
 */
class MainInitTest extends PHPUnit {

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Event not Found!
     */
    public function testBootstrapEventExceptionWithoutParams() {

        $event = new \Event\Delegate();
        $manager = new Event\ObsManager();
        $client = new Client\Delegate($manager);

        $event->addEvent(new Event\RequestGet($client));
        $event->runEvents();

        $manager->notify();
    }

    public function testSequenceOfExecution() {
        $event = new \Event\Delegate();
        $manager = new Event\ObsManager();
        $client = new Client\Delegate($manager);

        $event->addEvent(new GETTest($client));
        $event->runEvents();
        $this->expectOutputString('execute here');
        $manager->notify();
    }

}

class GETTest extends Event\AbstractEvent {

    public function has() {
        return true;
    }

    public function listen() {
        $this->client->addClient(new ControllerTest);
    }

}

class ControllerTest extends \Client\AbstracClient {

    public function execute() {
        echo 'execute here';
    }

    public function hasRequest() {
        return true;
    }

}
