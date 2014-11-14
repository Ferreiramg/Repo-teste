<?php

namespace Model;

/**
 * Description of ProdutorReport
 *
 * @author Administrador
 */
class ProdutorReport {

    public function resumeInfoEntradas($id = 1, $media = EntradaEntityIterator::KG_60, $ano = null) {
        $conn = Connection\Init::getInstance()->on();
        $produtor = new Produtor();
        $ano = is_null($ano) ? date('Y') : $ano;
        $stmt = $conn->query(sprintf("SELECT * FROM entradas WHERE _cliente = %u AND ano ='%s' ORDER BY data ASC", $id, $ano));
        $produtor->setIdKey($id - 1);

         $total = 0; $saida = 0; $qp = 0; $trasferencia = 0; $servico = 0; $imp = 0; $corrgido = 0;
        $output = array('content' => []);
        $entradas = new \Client\EntradaRead();
        $entradas->params[1] = $id;

        $calendar = $entradas->calendarData($media);
        $a = 0;
        $c = 0;
        if ($stmt) {
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if (!empty($calendar) && !empty($data)) {
                $c = count($calendar) - 1;
                for ($i = 0; $i < $c; ++$i) {
                    $a +=$calendar[$i]['desconto'];
                }
            }
            foreach ($data as $values) {
                if ($values['foi_transf'] == "0")
                    $total += $values['peso'];
                else
                    $trasferencia +=$values['peso'];

                $saida += $values['saida_peso'];
                $qp += $values['quebra_peso'];
                $imp += $values['desc_impureza'];
                $servico += $values['servicos'];
                $corrgido += $values['peso_corrigido'];
                $output['content'][] = [
                    'id' => $values['id'],
                    'entrada' => $values['peso'],
                    'saida' => $values['saida_peso'],
                    'umidade' => $values['umidade'],
                    'impureza' => $values['impureza'],
                    'q_pKg' => $values['quebra_peso'],
                    'impKg' => $values['desc_impureza'],
                    'servicosKg' => $values['servicos'],
                    'corrigido' => $values['peso_corrigido'],
                    'data' => date('d/m/Y', strtotime($values['data'])),
                    'obs' => $values['observacao']
                ];
            }
        }
        $output['agregado'] = [
            'nome' => $produtor->nome,
            'taxa' => $produtor->getTaxa(),
            'bruto' => $total,
            'liquido' => $corrgido,
            'saidas' => $saida,
            'transf' => $trasferencia,
            'qp' => $qp,
            'armazenagem' => $a,
            'imp' => $imp,
            'dias' => $c,
            'servicos' => $servico,
            'saldo' => round($corrgido - $saida, 2)
        ];
        unset($calendar);
        return $output;
    }

}
