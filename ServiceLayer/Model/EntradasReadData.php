<?php

namespace Model;

/**
 * Description of EntradasReadData
 *
 * @author Luis Paulo
 */
class EntradasReadData {

    public function getdataByClientId($id) {
        $conn = Connection\Init::getInstance()->on();
        
        $stmt = $conn->prepare("SELECT *, SUM(saida_peso) as saida,"
                . " SUM(peso) as entrada,"
                . " SUM(peso_corrigido) as corrigido"
                . " FROM entradas"
                . " WHERE _cliente = :i GROUP BY data ORDER BY data ASC");

        $stmt->bindValue(':i', $id, \PDO::PARAM_INT);
        if ($stmt->execute())
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return [];
    }

}
