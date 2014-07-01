<?php

namespace Client;

use DateTime;

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
            echo json_encode($this->calendarData());
                return null;
            }
        }

    public function hasRequest() {
        return \Main::$Action === 'entrada_read';
    }

    private function calendarData() {
        $key = ($this->params[1] - 1); //get id produtor to array key
        $data = $this->data->getdataByClientId($this->params[1]);
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
