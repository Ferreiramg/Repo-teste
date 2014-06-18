<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Event;

use Client\ExecuteInterface;

/**
 * Description of Delete
 *
 * @author Laticinios PJ
 */
class Delete implements ObserverEvent {

    public function has() {
        return \Main::$REQUEST === 'DELETE';
    }

    public function update(ExecuteInterface $subject) {
        $subject->execute();
    }

}
