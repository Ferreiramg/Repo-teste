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
    public $cols = [], $pointer = 0;

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

        $__data = (object) $this->cols[$this->pointer];
        if ($value['dia'] === date('Y-m-d',  strtotime($__data->data)) ) {

            $entrada = round($__data->corrigido / $this->media, 2, PHP_ROUND_HALF_DOWN);
            $saida = round($__data->saida / $this->media, 2, PHP_ROUND_HALF_UP);
            self::$saldo += ($entrada - $saida);

            $value = array(
                'id' => $__data->id,
                'dia' => $value['dia'],
                'entrada' => $entrada,
                'saida' => $saida,
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

    public function setCliente(Produtor $cliente) {
        $this->cliente = $cliente;
    }

    public function getSaldo($deduction) {
        return static::$saldo -= $deduction;
    }

    public function deduction() {
        $taxa =  $this->cliente->getTaxa() / 100.0;
        return self::$saldo < 0 ? 0 :
                round($taxa * self::$saldo, 2, PHP_ROUND_HALF_UP);
    }

    public function __destruct() {
        unset($this);
    }
}
