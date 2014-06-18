<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \Main,
    \ReflectionClass,
    \Event\ObserverEvent;

/**
 * Description of DelegateEvent
 *
 * @author Laticinios PJ
 */
class DelegateEvent {

    private $client, $eventsController;

    public function addEvent(ObserverEvent $event) {
        $this->eventsController[] = $event;
        return $this;
    }

    public function runClient(Event\Manager $manager) {

        $this->applyClientFactory();
        $found = false;
        foreach ($this->eventsController as $controller) {
            if ($controller->has()) {
                $manager->attach($this->client);
                $found = true;
                break;
            }
        }
        if (!$found) {
            throw new \RuntimeException('Evente Not Found');
        }
    }

    private function applyClientFactory() {
        $class = Main::$Action;
        try {
            $reflect = new ReflectionClass($class);
            $this->client = $reflect->newInstance();
        } catch (\ReflectionException $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    public function getClient() {
        return $this->client;
    }

}
