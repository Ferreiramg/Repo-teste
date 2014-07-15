<?php

namespace Model\Cached;

/**
 * Description of TempTable
 *
 * @author Luis Paulo
 */
class TempTable {

    public $conn, $values, $ponter = 0;
    private $_it;

    public function __construct(\PDO $conn) {
        $this->conn = $conn;
        $this->createTable();
        $stmt = $this->conn->query("SELECT * FROM tmpReport");
        $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->_it = new \ArrayIterator($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    private function createTable() {
        if (!$this->conn->exec($this->sql)) {
            throw new \RuntimeException("Temp table was not created!");
        }
    }

    public function reload($id = false) {
        $sql = $id ? sprintf(" WHERE id=%u", $id) : "";
        $stmt = $this->conn->query("SELECT * FROM tmpReport$sql");
        $this->_it = null;
        return $this->_it = new \ArrayIterator($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function count() {
        return $this->_it->count();
    }

    public function save(&$stmt = null) {
        $stmt = $this->conn->prepare("INSERT INTO tmpReport (id,produtor,saldo,impureza_media,"
                . "service_secagem,quebra_peso, umidade_media, quebra_tecnica,service_armazenagem)"
                . "VALUES(:id,:p,:s,:i,:g,:qp,:u, :qt,:a)");
        $stmt->bindValue(':id', $this->id);
        $stmt->bindValue(':p', $this->produtor, \PDO::PARAM_STR);
        $stmt->bindValue(':s', $this->saldo);
        $stmt->bindValue(':i', $this->impureza_media);
        $stmt->bindValue(':u', $this->umidade_media);
        $stmt->bindValue(':g', $this->service_secagem);
        $stmt->bindValue(':qp', $this->quebra_peso);
        $stmt->bindValue(':qt', $this->quebra_tecnica);
        $stmt->bindValue(':a', $this->service_armazenagem);
        if ($stmt->execute()) {
            $this->values = [];
            return true;
        }
        return false;
    }

    public static function drop() {
        return \Model\Connection\Init::getInstance()->on()->exec("DROP TABLE tmpReport;");
    }

    public function __get($name) {
        if ($this->_it->offsetExists($this->ponter)) {
            return $this->_it->offsetGet($this->ponter)[$name];
        } elseif (isset($this->values[$name])) {
            return $this->values[$name];
        } else {
            return null;
        }
    }

    public function __set($name, $value) {
        if ($this->_it->offsetExists($this->ponter))
            $this->_it->offsetGet($this->ponter)[$name] = $value;
        else
            $this->values[$name] = $value;
    }

    public function __toString() {
        return json_encode($this->_it->getArrayCopy());
    }

    public static function initSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['tmp_data'] = true;
    }

    public static function unsetSession() {
        unset($_SESSION['tmp_data']);
    }

    public $sql = <<<SQL
CREATE TEMPORARY TABLE IF NOT EXISTS tmpReport(
    id INTEGER NOT NULL ,
    produtor TEXT NOT NULL ,
    saldo REAL NOT NULL DEFAULT 0.00,
    impureza_media REAL NOT NULL DEFAULT 0.00,
    umidade_media REAL NOT NULL DEFAULT 0.00,
    service_secagem REAL NOT NULL DEFAULT 0.00,
    quebra_peso REAL NOT NULL DEFAULT 0.00,   
    quebra_tecnica REAL NOT NULL DEFAULT 0.00,
    service_armazenagem REAL NOT NULL DEFAULT 0.00
);
SQL;

}
