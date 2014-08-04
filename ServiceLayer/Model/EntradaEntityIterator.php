<?php

namespace Model;

/**
 * EntradaEntityIterator apply data to storage iterator.
 * Make a calendar day iterator
 *
 * @author Administrador
 */
class EntradaEntityIterator extends \ArrayIterator {

    public $cliente;
    private $media = 60;
    public static $saldo = 0, $armazenagem = 0;
    public $cols = [], $pointer = 0, $qt = 0;

    const KG_60 = 60;
    const KG_50 = 50;

    public function __construct($media = self::KG_60, $array = []) {
        $this->media = $media;
        parent::__construct($array, \ArrayIterator::ARRAY_AS_PROPS);
    }

    public function setCols(array $data) {
        $this->cols = $data;
    }

    public function append($value) {
        static::$armazenagem +=$this->deduction();
        $__data = (object) $this->cols[$this->pointer];
        if ($value['dia'] === date('Y-m-d', strtotime($__data->data))) {
            $entrada = round($__data->corrigido / $this->media, 2, PHP_ROUND_HALF_DOWN);
            $saida = round($__data->saida / $this->media, 2, PHP_ROUND_HALF_UP);
            static::$saldo += ($entrada - $saida);
            static::$armazenagem +=$this->deduction();
            $value = array(
                'id' => $__data->id,
                'qt' => 0,
                'dia' => $value['dia'],
                'entrada' => $entrada,
                'saida' => $saida,
                'desconto' => $this->deduction(),
                'saldo' => static::$saldo,
                'observacao' => $__data->observacao
            );
            if (next($this->cols) !== false) {
                ++$this->pointer;
            }
        }
        parent::append($value);
    }

    public function setCliente(Produtor $cliente) {
        $this->cliente = $cliente;
    }

    public function getSaldo($deduction) {
        return static::$saldo -= $deduction;
    }

    public function getDeductionArmazenagem() {
        return static::$armazenagem;
    }

    public function deduction() {
        $taxa = $this->cliente->getTaxa() / 100.0;
        return static::$saldo < 0 ? 0 :
                round($taxa * static::$saldo, 2, PHP_ROUND_HALF_UP);
    }

    public function __destruct() {
        static::$armazenagem = 0;
        static::$saldo = 0;
    }

}
