<?php

namespace Client;

use DateTime,
    Model\Cached\Memory,
    Model\CalcDiscounts as Disconto;

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
        if (isset($this->params[0]) && isset($this->params[1]) && $this->params[0] == 'calendar') {
            $key = (string) $this->params[0] . ':' . $this->params[1];
            $_t = $this;
            echo Memory::getInstance()->checkIn($key, function(\Memcached $mem)use ($_t, $key) {
                $data = json_encode($_t->calendarData());
                $mem->set($key, $data, time() + 300);
                unset($_t);
                return $data;
            });
            return null;
        }
    }

    public function hasRequest() {
        return \Main::$Action === 'entrada_read';
    }

    /**
     * Make a Calendar ( should be in a model )
     * @return array
     * @throws \Exceptions\ClientExceptionResponse format error date
     */
    public function calendarData($media = \Model\EntradaEntityIterator::KG_60) {
        $key = ($this->params[1] - 1); //get id produtor to array key
        $data = $this->data->getdataByClientId($this->params[1]);

        if (empty($data)) {
            return [];
        }
        $iterator = new \Model\EntradaEntityIterator($media);
        $iterator->setCliente(new \Model\Produtor($key));
        $iterator->setCols($data);
        $hoje = new DateTime('now');
        $entrada = new DateTime($data[0]['data']);
        if (DateTime::getLastErrors()['warning_count']) { //error format date
            throw new \Exceptions\ClientExceptionResponse(
            print_r(DateTime::getLastErrors()['warnings'], true));
        }
        $i = 0;

        while ($entrada < $hoje) {
            $deduction = $iterator->deduction();
            $qt = $this->qt($entrada, $iterator->getSaldo($deduction));
            $saldo = $iterator->getSaldo($qt);
            $iterator->append([
                'id' => 0,
                'qt' => $qt,
                'dia' => $entrada->format('Y-m-d'),
                'entrada' => 0,
                'saida' => 0,
                'desconto' => $deduction,
                'saldo' => $saldo,
                'observacao' => ''
            ]);
            ++$i;
            $entrada = $entrada->modify('+1day');
        }
        return $iterator->getArrayCopy();
    }

    private function qt($entrada, $saldo) {
        if ($entrada->format('t-m') == $entrada->format('d-m') && $saldo > 0) {
            return Disconto::quebraTecnica($saldo);
        }
        return 0;
    }

}
