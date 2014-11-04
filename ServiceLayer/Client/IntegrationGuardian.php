<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Client;

/**
 * Description of IntegrationGuardian
 *
 * @author Luis
 */
class IntegrationGuardian extends AbstracClient {

    public function execute() {
        $model = new \Model\IntegrationGuardian();
        $ticket = \Model\Entrada::TicketFormat($this->params[0]);
        $array = $model->filterTicket($ticket);
        $array['conflito'] = $model->hasConflict($ticket);
        $json = html_entity_decode(json_encode($array));
        echo $json;
    }

    public function hasRequest() {
        return \Main::$Action === "guardinclude";
    }

}
