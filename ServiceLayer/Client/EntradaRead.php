<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Client;

/**
 * Description of EntradaRead
 *
 * @author Laticinios PJ
 */
class EntradaRead extends AbstracClient {

    public function execute() {
        
    }

    public function hasRequest() {
        return \Main::$Action === 'entrada_read';
    }

}
