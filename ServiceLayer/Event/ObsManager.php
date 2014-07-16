<?php

namespace Event;

use ArrayIterator,
    Client\ClientInterface as Client;

/**
 * Observer Client Events 
 * 
 * @see \Event\InterfaceEvent
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
