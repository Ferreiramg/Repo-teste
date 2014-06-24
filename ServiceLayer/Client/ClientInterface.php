<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
