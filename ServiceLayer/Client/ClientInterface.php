<?php

namespace Client;

/**
 *
 * @author Administrador
 */
interface ClientInterface {

    public function clientDelegate(Delegate $delegate);

    public function execute();

    public function hasRequest();

    public function update(\Event\InterfaceEvent $subject);
}
