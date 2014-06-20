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
    protected $cols;

    const KG_60 = 60;
    const KG_50 = 50;

    public function __construct($media = self::KG_60, $array = [], $flags = 0) {
        $this->media = $media;
        parent::__construct($array, $flags);
    }

    public function append($value) {
        if ($value['dia'] === $this->dia) {
            $this->entrada = round($this->entrada / $this->media, 2, PHP_ROUND_HALF_DOWN);
            $this->saida = round($this->saida / $this->media, 2, PHP_ROUND_HALF_UP);
            self::$saldo += ($this->entrada - $this->saida);
            $value = array(
                'dia' => $value['dia'],
                'entrada' => $this->entrada,
                'desconto' => $this->deduction(),
                'saldo' => self::$saldo
            );
        }
        parent::append($value);
    }

    public function setCliente(\Cliente $cliente) {
        $this->cliente = $cliente;
    }

    public function getSaldo($deduction) {
        return static::$saldo -= $deduction;
    }

    public function deduction() {
        $taxa = (float) 0.033 / 100.0;
        return self::$saldo < 0 ? 0 :
                round($taxa * self::$saldo, 2, PHP_ROUND_HALF_UP);
    }

    function __set($name, $value) {
        $this->cols[$name] = $value;
    }

    function __get($name) {
        return $this->cols[$name];
    }

}
