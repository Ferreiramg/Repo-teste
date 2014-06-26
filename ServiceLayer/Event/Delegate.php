<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Event;

/**
 * Description of _Delegate
 *
 * @author Administrador
 */
class Delegate {

    private $storage;

    public function addEvent(AbstractEvent $event) {
        $this->storage[] = $event;
        return $this;
    }

    public function runEvents() {
        foreach ($this->storage as $eventControll) {
            if ($eventControll->has()) {
                $eventControll->listen();
                return null;
            }
        }
        throw new \RuntimeException('Event not Found!');
    }

}
