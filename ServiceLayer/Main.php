<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Event\Manager;

/**
 * Description of Main
 *
 * @author Laticinios PJ
 */
class Main {

    public static $REQUEST = null;
    public static $Action = null;

    public function run($request, $action = null) {
        static::$REQUEST = $request;
        static::$Action = $action;
        $manager = new Manager();
        $delegate = new DelegateEvent();
        $delegate
                ->addEvent(new Event\Post)
                ->addEvent(new Event\Get)
                ->addEvent(new Event\Put)
                ->addEvent(new Event\Delete)
                ->runClient($manager);
        $manager->notify();
    }

}
