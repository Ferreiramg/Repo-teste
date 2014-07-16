<?php

namespace Client;

/**
 * Description of _Delegate
 *
 * @author Administrador
 */
class Delegate {

    private $storage, $observer;

    public function __construct(\Event\ObsManager $observer) {
        $this->observer = $observer;
    }

    /**
     * Add a client to storage
     * @param \Client\AbstracClient $client
     */
    public function addClient(AbstracClient $client) {
        $this->storage[] = $client->clientDelegate($this);
        $this->observer->attach($client);
    }

    /**
     * Execute a client and detach of observer
     * @return mixed
     * @throws \RuntimeException Client not Found
     */
    public function runClient() {
        foreach ($this->storage as $client) {
            if ($client->hasRequest()) {
                $this->observer->detach($client);
                return $client->execute();
            }
        }
        throw new \RuntimeException('Client not Found!');
    }

}
