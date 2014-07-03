<?php

namespace Event;

/**
 * Description of RequestPost
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
