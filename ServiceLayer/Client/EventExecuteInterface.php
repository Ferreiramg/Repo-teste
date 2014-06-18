<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Client;

/**
 * Description of EventExecuteInterface
 *
 * @author Laticinios PJ
 */
abstract class EventExecuteInterface implements ExecuteInterface {

    public function update(ExecuteInterface $subject) {
        $subject->execute();
    }

}
