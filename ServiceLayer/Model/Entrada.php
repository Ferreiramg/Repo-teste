<?php

namespace Model;

/**
 * Description of Entrada
 *
 * @author Administrador
 */
class Entrada {

    public $args, $csvfile, $error_msg = "";

    public function __construct() {
        $this->args = array(
            'id' => ['filter' => FILTER_VALIDATE_INT],
            'produtor' => ['filter' => FILTER_VALIDATE_INT],
            'tipo' => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 1, 'max_range' => 2]],
            'peso' => FILTER_SANITIZE_NUMBER_INT,
            'umidade' => ['filter' => FILTER_VALIDATE_FLOAT],
            'impureza' => ['filter' => FILTER_VALIDATE_FLOAT],
            'motorista' => ['filter' => FILTER_SANITIZE_STRING],
            'ticket' => ['filter' => FILTER_SANITIZE_STRING],
            'observacao' => ['filter' => FILTER_SANITIZE_STRING],
            'data' => ['filter' => FILTER_SANITIZE_STRING],
            'acao' => ['filter' => FILTER_SANITIZE_STRING]
        );
    }

    public function create(array $args, &$stmt = null) {
        $args['saida'] = 0;
        $args['corrigido'] = 0;
        if ($this->checkType($args['tipo'])) {
            $calcs = $this->instanceCalcDiscountsWillApplyFilter($args['umidade']);
            $qp = round($args['peso'] * $calcs->quebraPeso(), 2);
            $sv = round($args['peso'] * $calcs->servicoSecagem(), 2);
            $imp = $calcs->impureza($args['impureza'], $args['peso']);
            $args['corrigido'] = $args['peso'] - ($qp + $sv + $imp);
        } else {
            $qp = 0;
            $sv = 0;
            $imp = 0;
            $args['saida'] = $args['peso'];
            $args['umidade'] = 0;
            $args['impureza'] = 0;
            $args['peso'] = 0;
        }
        $this->error_msg = "Não pode ser inserido os dados!!";
        $con = Connection\Init::getInstance()->on();
        $stmt = $con->prepare("INSERT INTO `entradas` (`peso`, `saida_peso`, `peso_corrigido`, `_cliente`, `umidade`, `impureza`, `data`, `ticket`, `observacao`,`quebra_peso`,`servicos`,`desc_impureza`) VALUES (:p, :s, :crr, :_c, :u, :i, :d, :t, :o,:q,:b,:z)");
        $stmt->bindValue(':p', $args['peso']);
        $stmt->bindValue(':s', $args['saida']);
        $stmt->bindValue(':crr', $args['corrigido']);
        $stmt->bindValue(':_c', $args['produtor']);
        $stmt->bindValue(':u', $args['umidade']);
        $stmt->bindValue(':i', $args['impureza']);
        $stmt->bindValue(':d', date('Y-m-d H:s:i', strtotime($args['data'])));
        $stmt->bindValue(':t', $args['ticket']);
        $stmt->bindValue(':o', sprintf("%s: %s", $args['motorista'], $args['observacao']));
        $stmt->bindValue(':q', $qp);
        $stmt->bindValue(':b', $sv);
        $stmt->bindValue(':z', $imp);
        return $stmt->execute();
    }

    public function deletar(array $args) {
        $this->error_msg = "Não foi apagado! Tente novamente.";
        return Connection\Init::getInstance()
                        ->on()
                        ->exec(sprintf("DELETE FROM `entradas` WHERE id = %u", $args['id']));
    }

    private function checkType($type) {
        $this->type = $type;
        return $type === 1;
    }

    private function instanceCalcDiscountsWillApplyFilter($umidade) {
        $csv = new CSV($this->csvfile, ';');
        return new CalcDiscounts(
                new CSVFilter($csv, $umidade), $umidade);
    }

}
