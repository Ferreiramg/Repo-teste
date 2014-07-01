<?php

namespace Model;

/**
 * Description of Produtor
 *
 * @author Luís Paulo
 */
class Produtor extends \ArrayIterator {

//    public $id, $nome, $grao, $data, $armazenagem;
    private $idKey;

    public function __construct($id = 0) {
        $this->setIdKey($id);
        $conn = Connection\Init::getInstance()->on();
        $stmt = $conn->query("SELECT * FROM cliente");
        parent::__construct($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function setIdKey($id) {
        $this->idKey = $id;
    }

    public function getTaxa() {
        return (double) $this->armazenagem;
    }

    public function __get($name) {
        return $this->offsetGet($this->idKey)[(string) $name];
    }

    public function __toString() {    
        return json_encode($this->getArrayCopy());
    }

}
