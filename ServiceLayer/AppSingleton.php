<?php

/**
 * Singleton Global
 * @author Luís Paulo <lpdeveloper@hotmail.com.br>
 * @version 1.0
 */
trait AppSingleton {

    private static $instance;

    private function __construct() {
        $this->init();
    }

    /**
     * Handle a single instance of the object
     * @return \AppSingleton
     */
    public static function getInstance() {
        return (self::$instance instanceof self) === false ? self::$instance = new self : self::$instance;
    }

}
