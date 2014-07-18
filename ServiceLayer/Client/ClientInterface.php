<?php

namespace Client;

/**
 *
 * @author Administrador
 */
interface ClientInterface {

    public function clientDelegate(Delegate $delegate);

    /**
     * Execute controller
     */
    public function execute();

    /**
     * True if the request, triggers the execute
     * @return bool true|false
     */
    public function hasRequest();

    public function update(\Event\InterfaceEvent $subject);
}
