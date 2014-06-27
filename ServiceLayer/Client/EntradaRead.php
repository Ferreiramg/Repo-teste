<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Client;

/**
 * Description of EntradaRead
 *
 * @author Laticinios PJ
 */
class EntradaRead extends AbstracClient {

    private $data;

    public function execute() {
        
    }

    public function hasRequest() {
        return \Main::$Action === 'entrada_read';
    }

    private function calendarData() {
        $iterator = new \Model\EntradaEntityIterator();

        $hoje = new DateTime('now');
        $entrada = new DateTime('01-05-2014');
        $i = 0;
        while ($entrada < $hoje) {
            $deduction = $iterator->deduction();
            $iterator->append([
                'dia' => $entrada->format('Y-m-d'),
                'entrada' => 0,
                'desconto' => $deduction,
                'saldo' => $iterator->getSaldo($deduction),
                'observacao' => ''
            ]);
            $entrada = $entrada->modify('+1day');
            ++$i;
        }
    }

}
