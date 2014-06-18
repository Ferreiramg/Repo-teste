<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Event;

use Client\ExecuteInterface;

/**
 *
 * @author Laticinios PJ
 */
interface ObserverEvent {

    public function has();

    public function update(ExecuteInterface $subject);
}
