<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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

    public function addClient(AbstracClient $client) {
        $this->storage[] = $client->clientDelegate($this);
        $this->observer->attach($client);
    }

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
