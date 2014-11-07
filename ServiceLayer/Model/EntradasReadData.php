<?php

namespace Model;

/**
 * Description of EntradasReadData
 *
 * @author Luis Paulo
 */
class EntradasReadData {

    private $id = 0;

    public function getdataByClientId($id) {
        $conn = Connection\Init::getInstance()->on();

        $stmt = $conn->prepare("SELECT *, SUM(saida_peso) as saida,"
                . " SUM(peso) as entrada,"
                . " SUM(peso_corrigido) as corrigido"
                . " FROM entradas"
                . " WHERE _cliente = :i GROUP BY data ORDER BY data ASC");
        if ($stmt) {
            $stmt->bindValue(':i', $id, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        return [];
    }

    protected function getData() {
        $conn = Connection\Init::getInstance()->on();
        $stmt = $conn->query(
                sprintf("SELECT * FROM entradas WHERE _cliente = %u ORDER BY id DESC", $this->id)
        );
        if ($stmt) {
            $data = array();
            foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $value) {
                $data[] = [
                    'id' => $value['id'],
                    'peso' => $value['peso'],
                    'saida_peso' => $value['saida_peso'],
                    'peso_corrigido' => $value['peso_corrigido'],
                    '_cliente' => $value['_cliente'],
                    'quebra_peso' => $value['quebra_peso'],
                    'servicos' => $value['servicos'],
                    'desc_impureza' => $value['desc_impureza'],
                    'umidade' => $value['umidade'],
                    'impureza' => $value['impureza'],
                    'ano' => $value['ano'],
                    'foi_transf' => $value['foi_transf'],
                    'data' => date('d/m/Y', strtotime($value['data'])),
                    'ticket' => Entrada::TicketFormat($value['ticket']),
                    'observacao' => $value['observacao'],
                    'group' => round($value['peso']) > 0 ? 'Entradas' : 'Saidas'];
            }
            return $data;
        }
        return [];
    }

    public function setId($id) {
        $this->id = (int) $id;
    }

    public function hash($key) {
        return (string) $key . $this->id;
    }

    public function __toString() {
        return json_encode($this->getData(),JSON_UNESCAPED_UNICODE);
    }

}
