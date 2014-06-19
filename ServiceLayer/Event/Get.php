<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Event;

/**
 * Description of Get
 *
 * @author Laticinios PJ
 */
class Get implements ObserverEvent {

    public function has() {
        return \Main::$REQUEST === 'GET';
    }

    public function update(\Client\ExecuteInterface $subject) {
        $subject->execute();
    }

}
