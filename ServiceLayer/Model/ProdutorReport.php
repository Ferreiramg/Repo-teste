<?php

namespace Model;

/**
 * Description of ProdutorReport
 *
 * @author Administrador
 */
class ProdutorReport {

    private $csvfile;

    public function resumeInfoEntradas($id = 1) {
        $this->csvfile = ROOT . \Configs::getInstance()->app->csv;
        $conn = Connection\Init::getInstance()->on();
        $produtor = new Produtor();
        $stmt = $conn->query(sprintf("SELECT * FROM entradas WHERE _cliente = %u ORDER BY data ASC", $id));
        $produtor->setIdKey($id - 1);

        $iterator = new \Model\EntradaEntityIterator();
        $iterator->setCliente($produtor);

        static $total = 0, $saida = 0, $qp = 0, $servico = 0, $imp = 0, $corrgido = 0;
        $output = array();
        $entradas = new \Client\EntradaRead();
        $entradas->params[1] = $id;
        $entradas->calendarData();
        if ($stmt) {
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $iterator->setCols($data);
            foreach ($data as $values) {
                $total += $values['peso'];
                $saida += $values['saida_peso'];
                $qp += $values['quebra_peso'];
                $imp += $values['desc_impureza'];
                $servico += $values['servicos'];
                $corrgido += $values['peso_corrigido'];
                $output[] = [
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
            'taxa'=>$produtor->getTaxa(),
            'bruto' => $total,
            'liquido' => $corrgido,
            'saidas' => $saida,
            'armazenagem' => $iterator->getDeductionArmazenagem(),
            'qp' => $qp,
            'imp' => $imp,
            'servicos' => $servico,
            'saldo' => round($corrgido - $saida, 2, PHP_ROUND_HALF_DOWN),
        ];
        return $output;
    }

}
