<?php

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

        $main = new Main();
        $main->run(null);
    }

    public function testHasDefined() {
        $this->assertTrue(defined('ROOT'));
    }

    public function testSequenceOfExecution() {
        $event = new Event\Delegate();
        $manager = new Event\ObsManager();
        $client = new Client\Delegate($manager);

        $event->addEvent(new GETTest($client));
        $event->runEvents();
        $this->expectOutputString('execute here');
        $manager->notify();
    }

    public function testUrlRewrite() {
        $_SERVER['REQUEST_URI'] = '/test/htaccess';
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $main = new Main();
        $main->urlRewrite();
        $this->assertEquals(Main::$Action, 'test');
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
