<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Event;

use Client\ExecuteInterface;

/**
 * Description of Put
 *
 * @author Laticinios PJ
 */
class Put implements ObserverEvent{

    public function has() {
        return \Main::$REQUEST === 'PUT';
    }

    public function update(ExecuteInterface $subject) {
        $subject->execute();
    }

}
