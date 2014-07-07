<?php

namespace Model;

/**
 * Description of ProdutorReport
 *
 * @author Administrador
 */
class ProdutorReport {

    public function resumeInfoEntradas() {
        $conn = Connection\Init::getInstance()->on();
        $tabletmp = new Cached\TempTable($conn);
        $produtor = new Produtor();
        for ($produtor->rewind(); $produtor->valid(); $produtor->next()) {
            
        }
    }

}
