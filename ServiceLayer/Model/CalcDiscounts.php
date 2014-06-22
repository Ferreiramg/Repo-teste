<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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

    public function setUmidade($u = null) {
        $this->umidade = $u ? $u : $this->umidade;
        return $this;
    }

    public static function quebraTecnica($peso) {
        return round((static::QUEBRA_TECNICA / 100.0) * ($peso), 2, PHP_ROUND_HALF_UP);
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
     * 
     * @return array
     * @throws RuntimeException %s , não é multiplo de dois
     */
    private function getCsvDataFiltering() {
        $out = array();
        for ($this->csv->rewind(); $this->csv->valid(); $this->csv->next()) {
            if ($this->csv->accept()) {
                $out = $this->csv->current();
                break;
            }
        }
        if (empty($out)) {
            throw new RuntimeException(sprintf("%s , não é multiplo de dois", $this->csv->getParam()));
        }
        return $out;
    }

}
