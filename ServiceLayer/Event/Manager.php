<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Event;

use Client\ExecuteInterface;

/**
 * Description of Manager
 *
 * @author Laticinios PJ
 */
class Manager implements ExecuteInterface {

    private $storage;

    public function __construct() {
        $this->storage = new \ArrayIterator();
    }

    public function execute() {
        return $this->storage->current()->execute();
    }

    public function attach(ExecuteInterface $observer) {
        $this->storage->append($observer);
    }

    public function detach(ExecuteInterface $observer) {
        foreach ($this->storage as $key => $objval) {
            if ($objval === $observer) {
                $this->storage->offsetUnset($key);
            }
        }
    }

    public function notify() {
        foreach ($this->storage as $subject) {
            $subject->update($this);
        }
    }

}
