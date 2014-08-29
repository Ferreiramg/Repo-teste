<?php

namespace Model;

/**
 * Description of Silo
 *
 * @author Luis Paulo
 */
class Silo {

    public function insertTotalArmazenagem() {
        $conn = Connection\Init::getInstance()->on();
        $out = array('amz' => 0, 'qp' => 0);
        $data = new \Model\ProdutorReport();
        foreach (new Model\Produtor as $value) {
            $out['amz'] += $data->resumeInfoEntradas($value['id'], 1)['agregado']['armazenagem'];
            $out['qp'] += $data->resumeInfoEntradas($value['id'], 1)['agregado']['qp'];
        }
        $ano = date('Y');
        $d = date('Y-m-d H:i:s');
        $exec = $conn->exec(sprintf("INSERT INTO `caixasilo` (quebra_peso,armazenagem,ano,data)"
                        . "VALUES(%f,%f,'%s','%s')", $out['qp'], $out['amz'], $ano, $d));
        if ($exec)
            return true;
        throw new \RuntimeException(print_r($conn->errorInfo(), true));
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

    public function siloPServicos() {

        $this->error_msg = "Sem dados para gerar grafico!";
        $conn = Connection\Init::getInstance()->on();
        $out = array(
            'labels' => array(),
            'datasets' => array(),
            'total' => 0
        );
        $sql = "SELECT EXTRACT( YEAR_MONTH FROM data ) AS data FROM `entradas`
                    GROUP BY EXTRACT( YEAR_MONTH FROM data );";
        $sql2 = "SELECT data,SUM( servicos ) as servicos FROM  `entradas` 
                        WHERE EXTRACT( YEAR_MONTH FROM data ) =  '%s'";
        $stmt = $conn->query($sql);
        if ($stmt)
            foreach ($stmt->fetchAll(2) as $v) {
                $stmt2 = $conn->query(sprintf($sql2, $v['data']));
                $data = $stmt2->fetchAll(2);
                foreach ($data as $key => $value) {
                    $out['labels'][] = date('M', strtotime($value['data']));
                    $out['datasets'][0]['data'][] = $p = round($value['servicos'], 2);
                    $out['total'] += $p;
                }
            }
        return $out;
    }

    public function simulador(array $args) {
        $imp = 0;
        $servico = 0;
        $qp = 0;
        $descontos = [0, 0, 0, 0];
        if ($args['umidade'] && $args['impureza']) {
            $calc = new CalcDiscounts(new CSVFilter(
                    new CSV($args['csv'], ';'), $args['umidade']), $args['umidade']);

            $imp = $calc->impureza($args['impureza'], $args['peso']);
            $servico = $calc->servicoSecagem();
            $qp = $calc->quebraPeso();
            $descontos = $calc->current();
        }
        $taxa = ($args['taxa'] / 100.0);
        $armz = round(($args['dias'] * $taxa) * $args['peso'], 2);

        return [
            'armz' => $armz,
            'imp' => $imp,
            'desc' => $descontos,
            'servico' => $servico,
            'q_p' => $qp
        ];
    }

}
