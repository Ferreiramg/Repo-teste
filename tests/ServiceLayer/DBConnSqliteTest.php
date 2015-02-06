<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataAccessAbstractTest
 *
 * @author Luis Paulo
 */
class DBConnSqliteTest {

    public static function ConnPDO() {
        \Configs::getInstance()->set('debug', true);
        $con = Model\Connection\Init::getInstance()->on();
        $con->exec(static::$sql);
        return $con;
    }

    public function testSelectData() {
        $con = $this->ConnPDO();
        $query = $con->query("SELECT * FROM entradas");

        $this->assertCount(2, $query->fetchAll());
    }

    private static $sql = <<<SQL
CREATE TABLE caixasilo (
  `id` INTEGER PRIMARY KEY,
  `servico` REAL,
  `quebra_peso` REAL,
  `armazenagem` REAL,
  `data` TEXT NOT NULL,
  `ano` TEXT
);
BEGIN TRANSACTION;
    INSERT INTO `caixasilo` 
    (`id`, `servico`, `quebra_peso`, `armazenagem`, `data`, `ano`) VALUES
    (52014, '3525', '500','4500', '2014-05-28', '2014');
COMMIT;  
CREATE TABLE IF NOT EXISTS cliente (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `nome` TEXT NOT NULL,
  `email` TEXT NULL,
  `grao` TEXT NOT NULL,
  `data` TEXT NOT NULL,
  `armazenagem` REAL
);         
BEGIN TRANSACTION;
    INSERT INTO `cliente` 
    (`id`, `nome`, `grao`, `data`, `armazenagem`) VALUES
    (1, 'Luis Paulo', 'Milho', '2014-05-28 16:52:29', 0.033);
   INSERT INTO `cliente` 
    (`id`, `nome`, `grao`, `data`, `armazenagem`) VALUES
    (2, 'Ferreira', 'Milho', '2014-06-28 16:52:29', 0.043);
COMMIT;          
CREATE TABLE `entradas` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `peso` REAL,
  `saida_peso` REAL,
  `peso_corrigido` REAL,
  `_cliente` INT NOT NULL,
   `quebra_peso` REAL NULL,
   `servicos` REAL NULL,
   `desc_impureza` REAL NULL,
  `umidade` REAL,
  `impureza` REAL,
  `ano` TEXT DEFAULT '2015',
  `foi_transf` TEXT DEFAULT 0,
  `data` TEXT NOT NULL,
  `ticket` TEXT NULL,
  `observacao` TEXT NULL
);
   BEGIN TRANSACTION;         
INSERT INTO `entradas` (`id`, `peso`, `saida_peso`, `peso_corrigido`, `_cliente`, `umidade`, `impureza`, `data`, `ticket`, `observacao`)
    VALUES (1, 13000, 0, 11930, 1, 16, 1, '2014-05-06', '', ' ;');
INSERT INTO `entradas` (`id`, `peso`, `saida_peso`, `peso_corrigido`, `_cliente`, `umidade`, `impureza`, `data`, `ticket`, `observacao`)
    VALUES (2, 13000, 0, 11930, 1, 16, 1, '2014-05-29', '', ' ;');
COMMIT;
SQL;

}
