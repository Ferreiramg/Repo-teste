<?php

namespace Model;

/**
 * Description of ProdutorCharts
 *
 * @author Luis Paulo
 */
class ProdutorCharts {

    private $produtor;
    public $error_msg = "Action not Found!";

    public function __construct(Produtor $produtor) {
        $this->produtor = $produtor;
    }

    public function outinchart() {
        $conn = Connection\Init::getInstance()->on();
        $sql = "SELECT id,data,SUM(saida_peso)as saida, SUM(peso) as entrada FROM entradas WHERE _cliente = %u GROUP BY data";
        $response = $conn->query(sprintf($sql, $this->produtor->id));
        $this->error_msg = "Sem dados para gerar grafico!";
        if ($response) {
            return $response->fetchAll(\PDO::FETCH_ASSOC);
        }
    }

}
