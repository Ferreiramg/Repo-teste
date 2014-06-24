<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Main
 *
 * @author Laticinios PJ
 */
class Main {

    public static $REQUEST = null;
    public static $Action = null;

    public function run($request, $action = null) {
        static::$REQUEST = $request;
        static::$Action = $action;
    }

}
