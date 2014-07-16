<?php

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

    /**
     * Listening to a Client
     */
    abstract public function listen();

    /**
     * True if the request, triggers the execute
     * @return bool true || false
     */
    abstract public function has();
}
