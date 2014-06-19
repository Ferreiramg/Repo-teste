<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Event;

/**
 * Description of Post
 *
 * @author Luis Paulo
 */
class Post implements ObserverEvent {

    public function has() {
         return \Main::$REQUEST === 'POST';
    }

    public function update(\Client\ExecuteInterface $subject) {
        $subject->execute();
    }

}
