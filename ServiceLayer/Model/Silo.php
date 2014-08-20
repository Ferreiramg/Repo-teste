<?php

namespace Model;

/**
 * Description of Silo
 *
 * @author Luis Paulo
 */
class Silo {

    public function create(array $args) {
        $conn = Connection\Init::getInstance()->on();
    }

    public function totalEstocado($ano = null, $kg = 60) {
        $ano = $ano ? $ano : date('Y');
        $conn = Connection\Init::getInstance()->on();
        $query = $conn->query(sprintf("SELECT SUM(  `peso_corrigido` ) AS corrigido,
            SUM( servicos ) AS ts,
            SUM( saida_peso ) AS saida
            FROM  `entradas` 
            WHERE ano = '%s'", $ano));
        if ($query) {
            $row = $query->fetchAll(2)[0];
            return array(
                'corrigido' => $peso = round($row['corrigido'] / $kg, 1),
                'ts' => round($row['ts'] / $kg, 1),
                'retirado' => $s = round($row['saida'] / $kg, 1),
                'espaco' => 50000 - ($peso > $s ? $peso - $s : $s - $peso),
                'amz' => ($peso > $s ? $peso - $s : $s - $peso)
            );
        }
        return array('corrigido' => 0, 'ts' => 0, 'retirado' => 0, 'espaco' => 0);
    }

}
