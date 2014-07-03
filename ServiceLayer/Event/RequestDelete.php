<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Event;

/**
 * Description of RequestDelete
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
