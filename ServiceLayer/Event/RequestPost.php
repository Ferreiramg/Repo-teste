<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
    }

}
