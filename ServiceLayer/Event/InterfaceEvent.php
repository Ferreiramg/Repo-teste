<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Event;

use Client\ClientInterface as client;

/**
 *
 * @author Administrador
 */
interface InterfaceEvent {

    public function __construct();

    public function attach(client $subject);

    public function detach(client $subject);

    public function notify();

    public function dispatch();
}
