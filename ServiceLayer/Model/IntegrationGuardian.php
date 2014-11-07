<?php

namespace Model;

/**
 * Description of IntegrationGuardian
 *
 * @author Luis
 */
class IntegrationGuardian {

    private $iterator;

    public function __construct() {
        $file = Connection\Odbc::getInstance()->on();
        $this->iterator = new CSV($file);
    }

    public function hasConflict($ticket) {
        $conn = Connection\Init::getInstance()->on();
        $stm = $conn->query(sprintf("SELECT * FROM entradas WHERE ticket = '%s'", $ticket));
        $data = [];
        if ($stm) {
            $data = $stm->fetchAll(2);
            return ['has' => !empty($data), 'dados' => $data];
        }
        return ['has' => false];
    }

    public function fetchAll() {
        $data = array();
        foreach ($this->iterator as $value) {
            $data[] = [
                'placa' => $value[3],
                'ticket' => $value[2],
                'status' => $value[6],
                'peso_inicial' => round($value[19], 2),
                'peso_final' => round($value[23], 2),
                'peso_liguido' => round($value[30], 2),
                'data' => [
                    date('d/m/Y H:i:s', strtotime($value[21])),
                    date('d/m/Y H:i:s', strtotime($value[25])),
                    date('d/m/Y', strtotime($value[21]))],
                'emissor' => utf8_decode($value[17]),
                'motorista' => utf8_decode($value[87]),
                'observacao' => utf8_decode($value[16])
            ];
        }
        return $data;
    }

    public function filterTicket($params) {
        $filter = new FilterTicket($this->iterator, $params);
        for ($filter->rewind(); $filter->valid(); $filter->next()) {
            if ($filter->accept()) {
                $value = $filter->current();
                return [
                    'placa' => $value[3],
                    'ticket' => $value[2],
                    'status' => $value[6],
                    'peso_inicial' => round($value[19], 2),
                    'peso_final' => round($value[23], 2),
                    'peso_liguido' => round($value[30], 2),
                    'data' => [
                        date('d/m/Y H:i:s', strtotime($value[21])),
                        date('d/m/Y H:i:s', strtotime($value[25])),
                        date('d/m/Y', strtotime($value[21]))],
                    'emissor' => htmlentities($value[17]),
                    'motorista' => htmlentities($value[87]),
                    'observacao' => htmlentities($value[16])
                ];
            }
        }
        throw new \Exceptions\ClientExceptionResponse(sprintf("%s , Ticket não encontrado!", $filter->getParam()));
    }

}

class FilterTicket extends \FilterIterator {

    private $param;

    public function __construct(\Iterator $iterator, $params) {
        $this->param = $params;
        parent::__construct($iterator);
    }

    public function getParam() {
        return $this->param;
    }

    /**
     * 
     * @return bool
     */
    public function accept() {

        return ($this->param === $this->getInnerIterator()->current()[2] );
    }

}
