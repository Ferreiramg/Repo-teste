<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Event;

use Model\Cached\Memory;

/**
 * Description of _Get
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
    }

}
