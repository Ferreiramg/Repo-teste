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
        echo json_encode($model->filterTicket(\Model\Entrada::TicketFormat($this->params[0])));
    }

    public function hasRequest() {
        return \Main::$Action === "guardinclude";
    }

}
