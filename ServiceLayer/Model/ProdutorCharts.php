<?php

namespace Model;

/**
 * Description of ProdutorCharts
 *
 * @author Luis Paulo
 */
class ProdutorCharts {

    private $produtor;

    use LogTrait;

    public function __construct(Produtor $produtor) {
        $this->produtor = $produtor;
    }

    /**
     * 
     * @return array data
     */
    public function outinchart() {
        $conn = Connection\Init::getInstance()->on();
        $sql = "SELECT id,data,SUM(saida_peso)as saida, SUM(peso) as entrada FROM entradas"
                . " WHERE _cliente = %u AND ano = '%s' GROUP BY data";
        $response = $conn->query(sprintf($sql, $this->produtor->id, Silo::getSessionYear()));
        $this->error_msg = "Sem dados para gerar grafico!";
        $out = array(
            'labels' => array(),
            'datasets' => array(['data' => []], ['data' => []])
        );
        if ($response) {
            foreach ($response->fetchAll(\PDO::FETCH_ASSOC) as $values) {
                $out['labels'][] = date('d/M', strtotime($values['data']));
                $out['datasets'][0]['data'][] = $values['entrada'];
                $out['datasets'][1]['data'][] = $values['saida'];
            }
            return $out;
        }
        return $out;
    }

}
