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
    public static $saldo = 0;
    public $cols = [], $pointer = 0, $qt = 0;

    const KG_60 = 60;
    const KG_50 = 50;

    public function __construct($media = self::KG_60, $array = []) {
        $this->media = $media;
        parent::__construct($array, \ArrayIterator::ARRAY_AS_PROPS);
    }

    /**
     * Set Data to calendar
     * @param array $data
     */
    public function setCols(array $data) {
        $this->cols = $data;
    }

    /**
     * Make Calendar days with data db
     * @param array $value
     */
    public function append($value) {

        $__data = (object) $this->cols[$this->pointer];
        if ($value['dia'] === date('Y-m-d', strtotime($__data->data))) {
            $entrada = round($__data->corrigido / $this->media, 2, PHP_ROUND_HALF_DOWN);
            $saida = round($__data->saida / $this->media, 2, PHP_ROUND_HALF_UP);
            static::$saldo += ($entrada - $saida);
            $value = array(
                'id' => $__data->id,
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

    /**
     * 
     * @param \Model\Produtor $cliente
     */
    public function setCliente(Produtor $cliente) {
        $this->cliente = $cliente;
    }

    /**
     * Balance decreases
     * @param decimal $deduction
     * @return decimal
     */
    public function getSaldo($deduction) {
        return static::$saldo -= $deduction;
    }

    /**
     * Make deduction
     * @return decimal
     */
    public function deduction() {
        $taxa = $this->cliente->getTaxa() / 100.0;
        return static::$saldo < 0 ? 0 :
                round($taxa * static::$saldo, 2, PHP_ROUND_HALF_UP);
    }

    public function __destruct() {
        static::$saldo = 0;
    }

}
