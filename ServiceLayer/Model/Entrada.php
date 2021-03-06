<?php

namespace Model;

/**
 * Description of Entrada
 *
 * @author Administrador
 */
class Entrada {

    public $args, $csvfile;

    use LogTrait;

    const TYPE = 1;

    public function __construct() {
        $this->args = array(
            'id' => ['filter' => FILTER_VALIDATE_INT],
            'produtor' => ['filter' => FILTER_VALIDATE_INT],
            'tipo' => ['filter' => FILTER_VALIDATE_INT],
            'peso' => FILTER_VALIDATE_FLOAT,
            'umidade' => ['filter' => FILTER_VALIDATE_FLOAT],
            'impureza' => ['filter' => FILTER_VALIDATE_FLOAT],
            'motorista' => ['filter' => FILTER_SANITIZE_STRING],
            'ticket' => ['filter' => FILTER_SANITIZE_STRING],
            'observacao' => ['filter' => FILTER_SANITIZE_STRING],
            'wastrans' => 0,
            'data' => ['filter' => FILTER_SANITIZE_STRING],
            'acao' => ['filter' => FILTER_SANITIZE_STRING]
        );
    }

    public function create(array $args, &$stmt = null) {
        $args['saida'] = 0;
        $args['corrigido'] = 0;
        $trans = empty($args['wastrans']) ? 0 : $args['wastrans'];
        $this->error_msg = "Não pode ser inserido os dados!!";
        if (static::TYPE === $args['tipo']) {
            $calcs = $this->instanceCalcDiscountsWillApplyFilter($args['umidade']);
            $qp = round($args['peso'] * $calcs->quebraPeso(), 2);
            $sv = round($args['peso'] * $calcs->servicoSecagem(), 2);
            $imp = $calcs->impureza($args['impureza'], $args['peso']);
            $args['corrigido'] = $args['peso'] - ($qp + $sv + $imp);
        } else {
            $qp = 0;
            $sv = 0;
            $imp = 0;
            $args['umidade'] = 0;
            $args['impureza'] = 0;
            if ($args['tipo'] === 2) {
                $args['motorista'] = "";
                $args['saida'] = 0;
                $args['corrigido'] = $args['peso'];
            } else {
                $args['saida'] = $args['peso'];
                $args['peso'] = 0;
            }
        }
        $date = $this->validateDate($args['data']);
        $con = Connection\Init::getInstance()->on();
        $stmt = $con->prepare("INSERT INTO `entradas` (`peso`, `saida_peso`, `peso_corrigido`, `_cliente`, `umidade`, `impureza`, `data`, `ticket`, `observacao`,`quebra_peso`,`servicos`,`desc_impureza`,`foi_transf`, `ano`) VALUES (:p, :s, :crr, :_c, :u, :i, :d, :t, :o,:q,:b,:z,:l,:an)");
        $stmt->bindValue(':p', $args['peso']);
        $stmt->bindValue(':s', $args['saida']);
        $stmt->bindValue(':crr', $args['corrigido']);
        $stmt->bindValue(':_c', $args['produtor']);
        $stmt->bindValue(':u', $args['umidade']);
        $stmt->bindValue(':i', $args['impureza']);
        $stmt->bindValue(':d', $date);
        $stmt->bindValue(':t', $args['ticket']);
        $stmt->bindValue(':o', utf8_encode(sprintf("%s: %s", $args['motorista'], $args['observacao'])));
        $stmt->bindValue(':q', $qp);
        $stmt->bindValue(':b', $sv);
        $stmt->bindValue(':z', $imp);
        $stmt->bindValue(':l', $trans);
        $stmt->bindValue(':an', date('Y'));
        if ($stmt->execute())
            return $con->lastInsertId();
        return 0;
    }

    public function makeQT(array $args) {
        $args['tipo'] = 0;
        $args['peso'] = CalcDiscounts::quebraTecnica($args['peso'] * 60);
        $args['observacao'] = "Quebra tecnica!";
        return $this->create($args);
    }

    /**
     * 
     * @param string $tikc
     * @param int $c
     * @return string
     */
    public static function TicketFormat($tikc) {
        return str_pad(trim($tikc), 6, '0', STR_PAD_LEFT);
    }

    /**
     * 
     * @param array $args
     * @return int 
     */
    public function deletar(array $args) {

        $data = Connection\Init::getInstance()
                        ->on()
                        ->query(sprintf("SELECT * FROM `entradas` WHERE id = %u", $args['id']))->fetchAll();
        if (empty($data)) {
            $this->error_msg = "Está entrada já foi apagada!";
            return 0;
        }
        //delete caixasilo referente ao mes
        Silo::delete($data[0]['data']);

        $this->error_msg = "Não foi apagado! Tente novamente.";
        return Connection\Init::getInstance()
                        ->on()
                        ->exec(sprintf("DELETE FROM `entradas` WHERE id = %u", $args['id']));
    }

    private function validateDate($date) {
        $now = new \DateTime('now');
        try {
            $date = new \DateTime(str_replace('/', '-', $date));
            if (\DateTime::getLastErrors()['warning_count'] || $now < $date) { //error format date
                throw new \Exceptions\ClientExceptionResponse("A data informada não é valida!");
            }
        } catch (\Exception $e) {
            throw new \Exceptions\ClientExceptionResponse("A data informada não é valida!");
        }

        return $date->format('Y-m-d H:s:i');
    }

    private function instanceCalcDiscountsWillApplyFilter($umidade) {
        $csv = new CSV($this->csvfile, ';');
        return new CalcDiscounts(
                new CSVFilter($csv, $umidade), $umidade);
    }

}
