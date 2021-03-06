<?php

namespace Event;

use Model\Cached\Memory;

/**
 * Delegate RequestPost to Clients
 *
 * @author Administrador
 */
class RequestPost extends AbstractEvent {

    public function has() {
        return \Main::$REQUEST === 'POST';
    }

    /**
     * @codeCoverageIgnore
     */
    public function listen() {

        $this->client->addClient(new \Client\ControllerEntrada);
        $this->client->addClient(new \Client\Produtor);
        $this->client->addClient(new \Client\SiloActions);
        $this->client->addClient(new \Client\Email);
        Memory::getInstance()->delete();
    }

}
