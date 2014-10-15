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
    public $error_msg = "Action not Found!";

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
        $stmt = $con->prepare("INSERT INTO `cliente` (`nome`, `email`, `grao`, `data`, `armazenagem`) VALUES (:n, :m, :g, :d, :a)");
        $stmt->bindValue(':n', $args['nome']);
        $stmt->bindValue(':m', $args['email']);
        $stmt->bindValue(':g', $args['grao']);
        $stmt->bindValue(':a', $args['armazenagem']);
        $stmt->bindValue(':d', date('Y-m-d H:s:i'));
        return $stmt->execute();
    }

    public function update(array $args, &$stmt = null) {
        $con = Connection\Init::getInstance()->on();
        $this->setIdKey($args['id'] - 1);
        $stmt = $con->prepare("UPDATE `cliente` SET `nome`=:n,`email`=:m,`grao`=:g,`data`=:d,`armazenagem`=:a WHERE `id`=:i");
        $stmt->bindValue(':i', $args['id'], \PDO::PARAM_INT);
        $stmt->bindValue(':n', empty($args['nome']) ? $this->nome : $args['nome'] );
        $stmt->bindValue(':m', empty($args['email']) ? $this->email : $args['email'] );
        $stmt->bindValue(':g', empty($args['grao']) ? $this->grao : $args['grao']);
        $stmt->bindValue(':a', !isset($args['armazenagem']) ? $this->armazenagem : $args['armazenagem']);
        $stmt->bindValue(':d', date('Y-m-d H:s:i'));
        return $stmt->execute();
    }

    public function deletar(array $args) {
        $this->error_msg = "Não foi apagado! Tente novamente.";
        $del = Connection\Init::getInstance()
                        ->on()->exec(
                sprintf('DELETE FROM `cliente` WHERE id = %u', $args['id']));
        $entrada = new Entrada();
        $entrada->deletar($args);
        $this->reOrderId(); //Reset Auto_Increment ID clientes
        return $del;
    }

    private function reOrderId() {
        $conn = Connection\Init::getInstance()->on();
        $stmt = $conn->query("SELECT * FROM cliente");
        $i = 0;
        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $key => $value) {
            $conn->exec(
                    sprintf('UPDATE `cliente` SET `id` = %u WHERE `id` = %u', $key + 1, $value['id'])
            );
            ++$i;
        }
        $this->AutoIncrementRedefine($i);
    }

    public function AutoIncrementRedefine($id) {
        return Connection\Init::getInstance()
                        ->on()->exec(
                        sprintf('ALTER TABLE  `cliente` AUTO_INCREMENT =%u', $id));
    }

    private function validateArgs(array $args) {
        return isset($args['nome']) && isset($args['armazenagem']);
    }

}
