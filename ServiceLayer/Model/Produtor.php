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
    public $error_msg;

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

    public function create(array $args, &$stmt = null) {
        $this->error_msg = "Não pode ser inserido os dados!!";
        $con = Connection\Init::getInstance()->on();
        $stmt = $con->prepare("INSERT INTO `cliente` (`nome`, `grao`, `data`, `armazenagem`) VALUES (:n, :g, :d, :a)");
        $stmt->bindValue(':n', $args['nome']);
        $stmt->bindValue(':g', $args['grao']);
        $stmt->bindValue(':a', $args['taxa']);
        $stmt->bindValue(':d', date('Y-m-d H:s:i', strtotime($args['data'])));
        return $stmt->execute();
    }

    public function deletar(array $args) {
        $this->error_msg = "Não foi apagado! Tente novamente.";
        return Connection\Init::getInstance()
                        ->on()
                        ->exec(sprintf("DELETE FROM `cliente` WHERE id = %$1u;"
                                . "DELETE FROM `entradas` WHERE _cliente = %$1u", $args['id']));
    }

}
