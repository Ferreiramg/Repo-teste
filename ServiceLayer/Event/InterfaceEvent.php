<?php

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
