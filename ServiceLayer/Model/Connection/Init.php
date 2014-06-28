<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Model\Connection;

/**
 * Description of Init
 *
 * @author Laticinios PJ
 */
class Init {

    use \AppSingleton;

    static protected $conn;

    public function init() {
        if (\Configs::getInstance()->get('debug') === true) {
            static::$conn = new \Model\PDOConn("sqlite::memory:");
            return true;
        }
        $conf = \Configs::getInstance()->get('connection.mysql');
        static::$conn = new \Model\PDOConn($conf['dsn'], $conf['user'], $conf['pass']);
    }

    /**
     * 
     * @return PDO
     */
    public function on() {
        return static::$conn;
    }

}