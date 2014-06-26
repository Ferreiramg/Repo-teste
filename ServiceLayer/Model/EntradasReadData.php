<?php

namespace Model;

/**
 * Description of EntradasReadData
 *
 * @author Laticinios PJ
 */
class EntradasReadData {

    private $connection;

    public function __construct($conn) {
        $this->connection = $conn;
    }

    public function getdataByClient(\Cliente $client) {
        $sql = "SELECT entradas.*, SUM(saida_peso)as saida, SUM(peso) as entrada, SUM(peso_corrigido) as corrigido FROM entradas WHERE _cliente = :id GROUP BY data ORDER BY data ASC";
        $stm = $this->connection->prepare($sql);
        $stm->bindValue(':id', $client->getId());
        if ($stm->execute()) {
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        }
    }

}
