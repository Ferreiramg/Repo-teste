<?php

namespace Model;

/**
 * Description of ProdutorReport
 *
 * @author Administrador
 */
class ProdutorReport {

    private $csvfile;

    private function instanceCalcDiscountsWillApplyFilter($umidade) {
        $csv = new CSV($this->csvfile, ';');
        return new CalcDiscounts(
                new CSVFilter($csv, $umidade), $umidade);
    }

    public function resumeInfoEntradas($id = 1) {
        $this->csvfile = ROOT . \Configs::getInstance()->app->csv;
        $conn = Connection\Init::getInstance()->on();
        $disconts = $this->instanceCalcDiscountsWillApplyFilter(0);
        $tabletmp = new Cached\TempTable($conn);
        $produtor = new Produtor();
        $stmt = $conn->query(sprintf("SELECT * FROM entradas WHERE _cliente = %u", $id));
        if ($stmt) {
            
        }
    }

}
