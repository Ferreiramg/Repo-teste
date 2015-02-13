<?php

namespace Model;

/**
 * Description of Silo
 *
 * @author Luis Paulo
 */
class Silo {

    public function insertTotalArmazenagem($anterior = 0) {
        if ($this->getSessionYear() !== date('Y')) {
            return true;
        }
        $conn = Connection\Init::getInstance()->on();
        $out = array('amz' => 0, 'qp' => 0);
        $data = new \Model\ProdutorReport();
        foreach (new \Model\Produtor as $value) {
            $dados = $data->resumeInfoEntradas($value['id'], 1);
            $out['amz'] += $dados['agregado']['armazenagem'];
            $out['qp'] += $dados['agregado']['qp'];
        }
        $ano = date('Y');
        $d = date('Y-m-d H:i:s');
        $amz = round($out['amz'] - $anterior, 2);
        $exec = $conn->exec(sprintf("INSERT INTO `caixasilo` (id,quebra_peso,armazenagem,ano,data)"
                        . "VALUES(%u,%f,%f,'%s','%s')", (int) date('mY'), $out['qp'], $amz < 0 ? 0 : $amz, $ano, $d));
        if ($exec)
            return true;
        throw new \RuntimeException(print_r($conn->errorInfo(), true));
    }

    public function armzChart() {
        $conn = Connection\Init::getInstance()->on();
        $stmt = $conn->prepare("SELECT * FROM caixasilo WHERE ano = :a ORDER BY data ASC");

        $out = array(
            'perc' => array(),
            'labels' => array(' '),
            'datasets' => array(['data' => [0], 'data' => [0]])
        );
        if ($stmt) {
            $stmt->bindvalue(':a', $this->getSessionYear());
            $stmt->execute();
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $c = count($data);

            if ($c === 0) {
                $this->insertTotalArmazenagem();
                $stmt->execute();
                foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $values) {
                    $out['labels'][] = date('M', strtotime($values['data']));
                    $out['datasets'][0]['data'][] = round($values['armazenagem'] / 60, 2);
                }
                return $out;
            }
            $last_dados = $data[$c - 1];
            $date = new \DateTime($last_dados['data']);
            $date->modify('+30 days');
            $now = new \DateTime('now');

            $am = $last_dados['armazenagem'];
            if ($now >= $date) {
                $this->insertTotalArmazenagem($last_dados['armazenagem']);
                $stmt->execute();
                return $this->makeChartAmrz($stmt->fetchAll(\PDO::FETCH_ASSOC), $am, $out);
            }
            return $this->makeChartAmrz($data, $am, $out);
        }
        return $out;
    }

    public function getAllDataProdutores($ano = null) {
        $out = array('saldo' => 0, 'produtor' => []);
        $data = new \Model\ProdutorReport();
        foreach (new \Model\Produtor as $value) {
            $dados = $data->resumeInfoEntradas($value['id'], 1, $ano);
            $dados['agregado']['id'] = $value['id'];
            $out['produtor'][] = $dados['agregado'];
            $out['saldo'] += $dados['agregado']['liquido'];
        }
        return $out;
    }

    public function totalEstocado($ano = null, $kg = 60) {
        $ano = $ano ? $ano : $this->getSessionYear();
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
                'espaco' => 50000 - ( $peso - $s ),
                'amz' => ($peso - $s )
            );
        }
        return array('corrigido' => 0, 'ts' => 0, 'retirado' => 0, 'espaco' => 0);
    }

    static public function delete($data) {
        return Connection\Init::getInstance()->on()->exec(
                        sprintf("DELETE FROM `caixasilo`"
                                . " WHERE id = %u", (int) date('mY', strtotime($data))));
    }

    /**
     * @codeCoverageIgnore
     */
    public function siloPServicos() {
        $ano = $this->getSessionYear();
        $conn = Connection\Init::getInstance()->on();
        $out = array(
            'labels' => array(),
            'datasets' => array(),
            'total' => 0
        );
        $sql = "SELECT EXTRACT( YEAR_MONTH FROM data ) AS data FROM `entradas`
                    WHERE ano = '%s'
                    GROUP BY EXTRACT( YEAR_MONTH FROM data );";
        $sql2 = "SELECT data,SUM( servicos ) as servicos FROM  `entradas` 
                        WHERE EXTRACT( YEAR_MONTH FROM data ) =  '%s' 
                        AND ano = '%s';";
        $stmt = $conn->query(sprintf($sql, $ano));
        if ($stmt) {
            foreach ($stmt->fetchAll(2) as $v) {
                $stmt2 = $conn->query(sprintf($sql2, $v['data'], $ano));
                $data = $stmt2->fetchAll(2);
                foreach ($data as $key => $value) {
                    $out['labels'][] = date('M', strtotime($value['data']));
                    $out['datasets'][0]['data'][] = $p = round($value['servicos'], 2);
                    $out['total'] += $p;
                }
            }
        }
        return $out;
    }

    public function changeYear($year) {
        Cached\Memory::getInstance()->meminstance->set('year', $year, 60 * 60 * 24 * 1);
    }

    static public function getSessionYear() {
        return Cached\Memory::getInstance()->meminstance->get('year') ? Cached\Memory::getInstance()->meminstance->get('year') : date('Y');
    }

    public function simulador(array $args) {
        $imp = 0;
        $servico = 0;
        $qp = 0;
        $descontos = [0, 0, 0, 0];
        try {
            if (isset($args['umidade']) && $args['umidade'] && isset($args['impureza'])) {
                $csv = new CSV(ROOT . \Configs::getInstance()->app->csv, ';');
                $calc = new CalcDiscounts(new CSVFilter($csv, $args['umidade']
                        ), $args['umidade']);

                $imp = $calc->impureza($args['impureza'], $args['peso']);
                $servico = round($args['peso'] * $calc->servicoSecagem(), 2);
                $qp = round($args['peso'] * $calc->quebraPeso(), 2);
                $descontos = $calc->current();
            }
            $taxa = ($args['taxa'] / 100.0);
            $armz = round(($args['dias'] * $taxa) * $args['peso'], 2);
        } catch (\RuntimeException $e) {
            return [
                'msg' => $e->getMessage(),
                'armz' => 0,
                'imp' => 0,
                'desc' => 0,
                'servico' => 0,
                'q_p' => 0,
                'total' => 0
            ];
        }

        return [
            'armz' => $armz,
            'imp' => $imp,
            'desc' => $descontos,
            'servico' => $servico,
            'q_p' => $qp,
            'total' => $armz + $imp + $servico + $qp
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    private function makeChartAmrz($data, $am, $out) {
        foreach ($data as $values) {
            $valor = $am < $values['armazenagem'] ? $values['armazenagem'] - $am : $am - $values['armazenagem'];
            $out['labels'][] = date('M', strtotime($values['data']));
            $out['datasets'][0]['data'][] = round($valor / 60, 2);
        }
        return $out;
    }

}
