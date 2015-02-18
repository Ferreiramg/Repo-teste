<?php

namespace Model;

use RuntimeException;

/**
 * Description of CalcDiscounts
 *
 * @author Administrador
 */
class CalcDiscounts {

    const QUEBRA_TECNICA = 0.30;

    private $csv, $umidade;

    public function __construct(CSVFilter $csv, $umidade = 0) {
        $this->csv = $csv;
        $this->umidade = $umidade;
    }

    public function current() {
        return (array) $this->csv->current();
    }

    public function setUmidade($u = null) {
        $this->umidade = $u ? $u : $this->umidade;
        return $this;
    }

    public static function quebraTecnica($peso) {
        return (static::QUEBRA_TECNICA / 100.0) * $peso;
    }

    public function quebraPeso($umidade = null) {
        $this->setUmidade($umidade);
        return floatval($this->getCsvDataFiltering()[2]) / 100.0;
    }

    public static function impureza($imp, $peso) {
        return round(($imp * $peso) / 100.0, 2, PHP_ROUND_HALF_UP);
    }

    public function servicoSecagem($umidade = null) {
        $this->setUmidade($umidade);
        return floatval($this->getCsvDataFiltering()[1]) / 100.0;
    }

    /**
     * Apply validate in iterator CSV
     * @return array
     * @throws RuntimeException %s , não é multiplo de dois
     */
    private function getCsvDataFiltering() {

        for ($this->csv->rewind(); $this->csv->valid(); $this->csv->next()) {
            if ($this->csv->accept()) {
                return $this->csv->current();
            }
            //@codeCoverageIgnoreStart
        }
        //@codeCoverageIgnoreEnd
        throw new RuntimeException(sprintf("%s , não é multiplo de dois", $this->csv->getParam()));
    }

}
