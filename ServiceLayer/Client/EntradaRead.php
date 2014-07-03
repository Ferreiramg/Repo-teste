<?php

namespace Client;

use DateTime,
    Model\Cached\Memory;

/**
 * Description of EntradaRead
 *
 * @author Luis Paulo
 */
class EntradaRead extends AbstracClient {

    private $data;

    public function __construct() {
        $this->data = new \Model\EntradasReadData();
        $this->params = \Main::$EXTRA_PARAMS;
    }

    public function execute() {
        if (isset($this->params[0]) && $this->params[0] == 'calendar') {
            $key = (string)$this->params[0] .':'. $this->params[1];
            $_t = $this;
            echo Memory::getInstance()->checkIn($key, function($mem)use ($_t, $key) {
                $data = json_encode($_t->calendarData());
                $mem->set($key, $data, time() + 300);
                return $data;
            });
            return null;
        }
    }

    public function hasRequest() {
        return \Main::$Action === 'entrada_read';
    }

    private function calendarData() {
        $key = ($this->params[1] - 1); //get id produtor to array key
        $data = $this->data->getdataByClientId($this->params[1]);
        if(empty($data)){
            return [];
        }
        $iterator = new \Model\EntradaEntityIterator();
        $iterator->setCliente(new \Model\Produtor($key));
        $iterator->setCols($data);
        $hoje = new DateTime('now');
        $entrada = new DateTime($data[0]['data']);
        if (DateTime::getLastErrors()['warning_count']) {
            throw new \Exceptions\ClientExceptionResponse(
            print_r(DateTime::getLastErrors()['warnings'], true));
        }
        while ($entrada < $hoje) {
            $deduction = $iterator->deduction();
            $iterator->append([
                'id' => 0,
                'dia' => $entrada->format('Y-m-d'),
                'entrada' => 0,
                'saida' => 0,
                'desconto' => $deduction,
                'saldo' => $iterator->getSaldo($deduction),
                'observacao' => ''
            ]);
            $entrada = $entrada->modify('+1day');
        }
        return $iterator->getArrayCopy();
    }

}
