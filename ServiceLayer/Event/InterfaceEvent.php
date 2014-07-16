<?php

namespace Event;

use Client\ClientInterface as client;

/**
 *
 * @author Administrador
 */
interface InterfaceEvent {

    public function __construct();

    /**
     * Attach Client to storage 
     * @param \Client\ClientInterface $subject
     */
    public function attach(client $subject);

    /**
     * Detach Client of storage
     * @param \Client\ClientInterface $subject
     */
    public function detach(client $subject);

    /**
     * Notify Clients in storage
     */
    public function notify();

    /**
     * Dispatch events of Client
     */
    public function dispatch();
}
