<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Model;

use Exceptions\ClientExceptionResponse;

/**
 * Description of Entrada
 *
 * @author Administrador
 */
class Entrada extends \Base\Entradas {

    public $args, $csvfile;

    public function __construct() {
        $this->args = array(
            'id' => ['filter' => FILTER_VALIDATE_INT],
            'produtor' => ['filter' => FILTER_VALIDATE_INT],
            'tipo' => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 1, 'max_range' => 2]],
            'peso' => FILTER_SANITIZE_NUMBER_INT,
            'umidade' => ['filter' => FILTER_SANITIZE_STRING],
            'impureza' => ['filter' => FILTER_VALIDATE_FLOAT],
            'motorista' => ['filter' => FILTER_SANITIZE_STRING],
            'ticket' => ['filter' => FILTER_SANITIZE_STRING],
            'observacao' => ['filter' => FILTER_SANITIZE_STRING],
            'data' => ['filter' => FILTER_SANITIZE_STRING],
            'acao' => ['filter' => FILTER_SANITIZE_STRING]
        );
        $this->csvfile = realpath('opt/tabela.csv');
        parent::__construct();
    }

    public function create(array $args) {
        $type = $this->checkType($args['tipo']);
        if ($type) {
            $this->setPeso($args['peso']);
            $this->setSaidaPeso(0);
        } else {
            $this->setSaidaPeso($args['peso']);
            $this->setPeso(0);
            $this->setPesoCorrigido(0);
            $args['impureza'] = 0;
            $args['umidade'] = 0;
        }
        $this->setData(date("Y-m-d 00:00:00", strtotime($args['data'])))
                ->setImpureza($args['impureza'])
                ->setECliente($args['produtor'])
                ->setUmidade($args['umidade'])
                ->setTicket($args['ticket'])
                ->setObservacao(sprintf("%s ;%s", $args['motorista'], $args['observacao']));

        if ($type) {
            $calcs = $this->instanceCalcDiscountsWillApplyFilter();
            $quebra_peso = round($this->getPeso() * $calcs->quebraPeso(), 2);
            $impureza = $calcs->impureza($this->getImpureza(), $this->getPeso());
            $secagem = round($this->getPeso() * $calcs->servicoSecagem(), 2);
            $sum = ($quebra_peso + $impureza + $secagem);
            $this->setPesoCorrigido($this->getPeso() - $sum);
        }
        if ($this->save()) {
            throw new ClientExceptionResponse("Inserido com sucesso!", 1, ClientExceptionResponse::SUCCESS);
        }
    }

    public function deletar(array $args) {
        if (!is_null($this->setId($args['id'])->delete())) {
            return true;
        }
        throw new ClientExceptionResponse("O registro não foi Apagado!", 0, ClientExceptionResponse::DANGER);
    }

    private function checkType($type) {
        $this->type = $type;
        return $type === 1;
    }

    private function instanceCalcDiscountsWillApplyFilter() {
        $csv = new CSV($this->csvfile, ';');
        return new CalcDiscounts(
                new CSVFilter($csv, $this->getUmidade()), $this->getUmidade());
    }

}
