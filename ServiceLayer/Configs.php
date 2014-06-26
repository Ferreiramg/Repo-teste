<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Noodlehaus\Config;

/**
 * Description of DBConfig
 *
 * @author Luis Paulo
 */
class Configs {

    private static $CONF_FILE = 'app.conf.json';
    private $file;

    public function init() {
        $this->file = dirname(__DIR__) . '/opt/' . self::$CONF_FILE;
        if (!$this->exists($this->file)) {
            throw new RuntimeException(sprintf("Config DB file not found. Create new [ %s ] in [ ./opt ] folder!",  $this->file));
        }
        $this->_document = new Config($this->file);
    }

    public function getFile() {
        return $this->file;
    }

use ConfigTrait;
}
