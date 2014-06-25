<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Client;

use Event\InterfaceEvent as EventManager;

/**
 * Description of AbstracClient
 *
 * @author Administrador
 */
abstract class AbstracClient implements ClientInterface {

    public $delegate;

    public function clientDelegate(Delegate $delegateClass) {
        $this->delegate = $delegateClass;
        return $this;
    }

    public function update(EventManager $subject) {
        $subject->dispatch()->delegate->runClient();
    }

}