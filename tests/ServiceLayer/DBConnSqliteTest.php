<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Propel\Runtime\Propel,
    Propel\Runtime\Connection\ConnectionManagerSingle;

/**
 * Description of DataAccessAbstractTest
 *
 * @author Luis Paulo
 */
class DBConnSqliteTest {

    public static function ConnPROPEL() {
        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->setAdapterClass('default', 'sqlite');
        $manager = new ConnectionManagerSingle();
        $manager->setConfiguration(array(
            'dsn' => "sqlite::memory:"
        ));
        $serviceContainer->setConnectionManager('default', $manager);
        $serviceContainer->getConnection()->exec(static::$sql);
        return $serviceContainer->getConnection();
    }

    public static function ConnPDO() {
        $dbh = new PDO('sqlite::memory:');
        $dbh->exec(static::$sql);
        return $dbh;
    }

    private static $sql = <<<SQL
CREATE TABLE IF NOT EXISTS cliente (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `nome` TEXT NOT NULL,
  `grao` TEXT NOT NULL,
  `data` TEXT NOT NULL,
  `armazenagem` REAL
);         
BEGIN TRANSACTION;
    INSERT INTO `cliente` 
    (`id`, `nome`, `grao`, `data`, `armazenagem`) VALUES
    (1, 'Luis Paulo', 'Milho', '2014-05-28 16:52:29', 0.033);
COMMIT;          
CREATE TABLE IF NOT EXISTS `entradas` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `peso` REAL,
  `saida_peso` REAL,
  `peso_corrigido` REAL,
  `_cliente` INT NOT NULL,
  `umidade` REAL,
  `impureza` REAL,
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
