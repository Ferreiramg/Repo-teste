<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Model;

/**
 * Description of EntradaEntityIterator
 *
 * @author Administrador
 */
class EntradaEntityIterator extends \ArrayIterator {

    public $cliente;
    private $media = 60;
    public static $saldo = 0;
    public $cols = [], $pointer = 0;

    const KG_60 = 60;
    const KG_50 = 50;

    public function __construct($media = self::KG_60, $array = [], $flags = 0) {
        $this->media = $media;
        parent::__construct($array, $flags);
    }

    public function setCols(array $data) {
        $this->cols = $data;
    }

    public function append($value) {

        $__data = (object) $this->cols[$this->pointer];
        if ($value['dia'] === $__data->data) {

            $entrada = round($__data->corrigido / $this->media, 2, PHP_ROUND_HALF_DOWN);
            $saida = round($__data->saida / $this->media, 2, PHP_ROUND_HALF_UP);
            self::$saldo += ($entrada - $saida);

            $value = array(
                'dia' => $value['dia'],
                'entrada' => $entrada,
                'desconto' => $this->deduction(),
                'saldo' => self::$saldo,
                'observacao' => $__data->observacao
            );
            if (next($this->cols) !== false) {
                ++$this->pointer;
            }
        }
        parent::append($value);
    }

    public function setCliente(\Produtor $cliente) {
        $this->cliente = $cliente;
    }

    public function saldo() {
        return static::$saldo;
    }

    public function getSaldo($deduction) {
        return static::$saldo -= $deduction;
    }

    public function deduction() {
        $taxa = (float) $this->cliente->getTaxa() / 100.0;
        return self::$saldo < 0 ? 0 :
                round($taxa * self::$saldo, 2, PHP_ROUND_HALF_UP);
    }

}
