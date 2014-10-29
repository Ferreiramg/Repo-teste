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
                    'peso_inicial' => number_format(round($value[19], 2),1,'.',','),
                    'peso_final' => number_format(round($value[23], 2),1,'.',','),
                    'peso_liguido' => number_format(round($value[30], 2),1,'.',','),
                    'data' => [
                        date('d/m/Y H:i:s', strtotime($value[21])),
                        date('d/m/Y H:i:s', strtotime($value[25])),
                        date('d/m/Y', strtotime($value[21]))],
                    'emissor' => $value[17],
                    'motorista' => $value[87],
                    'observacao' => utf8_decode($value[16])
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
