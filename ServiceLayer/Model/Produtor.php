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
        $conn = Connection\Init::getInstance()->on();
        $stmt = $conn->query("SELECT * FROM cliente");
        parent::__construct($stmt->fetchAll(\PDO::FETCH_ASSOC));
        $this->setIdKey($id);
    }

    public function setIdKey($id) {
        if (!$this->offsetExists($id) && $this->count() > 0) {
            throw new \Exceptions\ClientExceptionResponse("Produtor Offset not Exists!");
        }
        $this->idKey = $id;
    }

    public function getSaldo() {
        $smt = Connection\Init::getInstance()->on()
                ->query("SELECT sum(peso_corrigido) as soma FROM entradas "
                . "WHERE _cliente=" . ++$this->idKey);
        $res = 0;
        if ($smt) {
            $res = (float) $smt->fetch(\PDO::FETCH_OBJ)->soma;
            $smt->closeCursor();
        }
        return $res;
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
        if (!$this->validateArgs($args)) {
            $this->error_msg = "Argumentos Produtor:Nome e Produtor:Taxa, estão faltando!";
            return false;
        }
        $con = Connection\Init::getInstance()->on();
        $stmt = $con->prepare("INSERT INTO `cliente` (`nome`, `grao`, `data`, `armazenagem`) VALUES (:n, :g, :d, :a)");
        $stmt->bindValue(':n', $args['nome']);
        $stmt->bindValue(':g', $args['grao']);
        $stmt->bindValue(':a', $args['taxa']);
        $stmt->bindValue(':d', date('Y-m-d H:s:i', strtotime($args['data'])));
        return $stmt->execute();
    }

    public function update(array $args, &$stmt = null) {
        $con = Connection\Init::getInstance()->on();
        $this->setIdKey($args['id'] - 1);
        $stmt = $con->prepare("UPDATE `cliente` SET `nome`=:n,`grao`=:g,`data`=:d,`armazenagem`=:a WHERE `id`=:i");
        $stmt->bindValue(':i', $args['id'], \PDO::PARAM_INT);
        $stmt->bindValue(':n', empty($args['nome']) ? $this->nome : $args['nome'] );
        $stmt->bindValue(':g', empty($args['grao']) ? $this->grao : $args['grao']);
        $stmt->bindValue(':a', empty($args['taxa']) ? $this->armazenagem : $args['taxa']);
        $stmt->bindValue(':d', date('Y-m-d H:s:i', strtotime($args['data'])));
        return $stmt->execute();
    }

    public function deletar(array $args) {
        $this->error_msg = "Não foi apagado! Tente novamente.";
        $del = Connection\Init::getInstance()
                        ->on()->exec(
                sprintf('DELETE FROM `cliente` WHERE id = %u', $args['id']));
        return $del && $this->reOrderId() ? 1 : 0;
    }

    private function reOrderId() {
        $count = $this->count() -1;
        $resp = true;
        $conn = Connection\Init::getInstance()->on();
        for ($index = 1; $index < $count; $index++) {
            $resp = $conn->exec(sprintf('UPDATE `cliente` SET `id` = %1$u WHERE `id` = %1$u', $index));
        }
        return $resp;
    }

    public function AutoIncrementRedefine() {
        //ALTER TABLE  `cliente` AUTO_INCREMENT =3

        return Connection\Init::getInstance()
                        ->on()->exec(
                        sprintf('ALTER TABLE  `cliente` AUTO_INCREMENT =%u', $args['id']));
    }

    private function validateArgs(array $args) {
        return isset($args['nome']) && isset($args['taxa']);
    }

}
