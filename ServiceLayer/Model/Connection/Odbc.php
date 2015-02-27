<?php

namespace Model\Connection;

/**
 * Description of Odbc
 *
 * @author Luis
 */
class Odbc {

    use \AppSingleton;

    static protected $file;

    public function init() {

        $mdb = \Configs::getInstance()->get('connection.odbc.dbq');
        $export = \Configs::getInstance()->get('connection.odbc.export');
        self::$file = ROOT . $export['csv'];
        if (\Configs::getInstance()->get('debug') === false) {
            $cmd = sprintf('sudo /usr/bin/mdb-export -Q -H %s "%s" > %s', $mdb, $export['table_name'], self::$file);
            exec($cmd);
        }
        return null;
    }

    /**
     * 
     * @return string file root
     */
    public function on() {
        if (!file_exists(self::$file)) {
            throw new \RuntimeException("ODBC not exported.");
        }
        return (string) self::$file;
    }

}
