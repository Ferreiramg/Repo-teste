<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Event;

use ArrayIterator,
    Client\ClientInterface as Client;

/**
 * Description of _Manager
 *
 * @author Administrador
 */
class ObsManager implements InterfaceEvent {

    private $storage;

    public function __construct() {
        $this->storage = new ArrayIterator();
    }

    public function attach(Client $subject) {
        $this->storage->append($subject);
    }

    public function detach(Client $subject) {
        foreach ($this->storage as $key => $objval) {
            if ($objval === $subject) {
                $this->storage->offsetUnset($key);
            }
        }
    }

    public function dispatch() {
        return $this->storage->current();
    }

    public function notify() {
        foreach ($this->storage as $subject) {
            $subject->update($this);
        }
    }

}
