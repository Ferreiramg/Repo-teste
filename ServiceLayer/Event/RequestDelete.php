<?php

namespace Event;

/**
 * Delegate RequestDelete event
 *
 * @author Administrador
 */
class RequestDelete extends AbstractEvent {

    public function has() {
        return \Main::$REQUEST === 'DELETE';
    }

    public function listen() {
        $this->client->addClient(new \Client\ControllerEntrada);
        $this->client->addClient(new \Client\Produtor);
    }

}
