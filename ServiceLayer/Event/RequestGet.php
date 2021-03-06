<?php

namespace Event;

use Model\Cached\Memory;

/**
 * Delegate GET event to Client
 *
 * @author Administrador
 */
class RequestGet extends AbstractEvent {

    public function has() {
        return \Main::$REQUEST === 'GET';
    }

    public function listen() {
        Memory::getInstance(); //init Server Mencached
        $this->client->addClient(new \Client\EntradaRead);
        $this->client->addClient(new \Client\ProdutorRead);
        $this->client->addClient(new \Client\ProdutorReport);
        $this->client->addClient(new \Client\ProdutorCharts);
        $this->client->addClient(new \Client\Silo);
        $this->client->addClient(new \Client\IntegrationGuardian);
        $this->client->addClient(new \Client\ExportarData);
    }

}
