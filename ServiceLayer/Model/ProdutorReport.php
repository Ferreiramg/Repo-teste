<?php

namespace Model;

/**
 * Description of ProdutorReport
 *
 * @author Administrador
 */
class ProdutorReport {

    private $csvfile;

    public function resumeInfoEntradas($id = 1, $media = EntradaEntityIterator::KG_60) {
        $this->csvfile = ROOT . \Configs::getInstance()->app->csv;
        $conn = Connection\Init::getInstance()->on();
        $produtor = new Produtor();
        $stmt = $conn->query(sprintf("SELECT * FROM entradas WHERE _cliente = %u ORDER BY data ASC", $id));
        $produtor->setIdKey($id - 1);

        static $total = 0, $saida = 0, $qp = 0, $qt = 0, $servico = 0, $imp = 0, $corrgido = 0;
        $output = array('content' => []);
        $entradas = new \Client\EntradaRead();
        $entradas->params[1] = $id;

        $calendar = $entradas->calendarData($media);
        $a = 0;
        $saldo = 0;
        $c = 0;
        if (!empty($calendar)) {
            $c = count($calendar) - 1;
            $a = 0;
            $saldo = round($calendar[$c]['saldo'], 2);
            for ($i = 0; $i < $c; ++$i) {
                $a +=$calendar[$i]['desconto'];
                $qt += $calendar[$i]['qt'];
            }
        }

        if ($stmt) {
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($data as $values) {
                $total += $values['peso'];
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
                    'data' => date('d/m/Y', strtotime($values['data']))
                ];
            }
        }
        $output['agregado'] = [
            'nome' => $produtor->nome,
            'taxa' => $produtor->getTaxa(),
            'bruto' => $total,
            'liquido' => $corrgido,
            'saidas' => $saida,
            'qp' => $qp,
            'qt' => $qt,
            'armazenagem' => $a,
            'imp' => $imp,
            'dias' => $c,
            'servicos' => $servico,
            'saldo' => $saldo,
        ];
        unset($calendar);
        return $output;
    }

}
