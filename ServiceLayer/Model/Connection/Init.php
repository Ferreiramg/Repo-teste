<?php

namespace Model\Connection;

/**
 * Init a Connection
 *
 * @author luis Paulo
 */
class Init {

    use \AppSingleton;

    static protected $conn;

    public function init() {
        try {
            if (\Configs::getInstance()->get('debug') === true) {
                static::$conn = new \Model\PDOConn("sqlite::memory:");
                return true;
            }
            $conf = \Configs::getInstance()->get('connection.mysql');
            static::$conn = new \Model\PDOConn($conf['dsn'], $conf['user'], $conf['pass']);
        } catch (\PDOException $e) {
            throw new \RuntimeException($e->getMessage());
        }
        return null;
    }

    /**
     * 
     * @return \PDO
     */
    public function on() {
        return static::$conn;
    }

    public function unsetConn() {
        static::$conn = null;
        self::$instance = null;
    }

}
