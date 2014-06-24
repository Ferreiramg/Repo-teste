<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Event;

/**
 * Description of AbstractEvent
 *
 * @author Administrador
 */
abstract class AbstractEvent {

    public $client;

    public function __construct(\Client\Delegate $delegate) {
        $this->client = $delegate;
    }

    abstract public function listen();

    abstract public function has();
}
