<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Client;

/**
 * Description of _Entrada
 *
 * @author Administrador
 */
class ControllerEntrada extends AbstracClient {

    public function execute() {
        
    }

    public function hasRequest() {
        return \Main::$Action === 'entrada';
    }

}
