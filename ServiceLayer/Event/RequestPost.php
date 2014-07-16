<?php

namespace Event;

/**
 * Delegate RequestPost to Clients
 *
 * @author Administrador
 */
class RequestPost extends AbstractEvent {

    public function has() {
        return \Main::$REQUEST === 'POST';
    }

    public function listen() {
        $this->client->addClient(new \Client\ControllerEntrada);
        $this->client->addClient(new \Client\Produtor);
    }

}
