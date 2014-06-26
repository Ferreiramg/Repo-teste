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
    public static $EXTRA_PARAMS = null;

    public function run($request, $action = null) {
        static::$REQUEST = $request;
        $this->urlRewrite($action);

        $event = new Event\Delegate();
        $manager = new Event\ObsManager();
        $client = new Client\Delegate($manager);

        $event->addEvent(new Event\RequestGet($client))
                ->addEvent(new Event\RequestPost($client));
        $event->runEvents();
        $manager->notify();
    }

    public function urlRewrite($custom = null) {

        if ($custom || !isset($_SERVER['REQUEST_URI'])) {
            static::$Action = $custom;
            return null;
        }
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $inputs['URI'] = substr_replace($_SERVER['REQUEST_URI'], '', 0, strlen($scriptName));
        $inputs['PARAM'] = explode('/', $inputs['URI']);
        static::$Action = trim($inputs['PARAM'][0], '/');
        array_shift($inputs['PARAM']);
        if ($inputs['PARAM'] !== "") {
            static::$EXTRA_PARAMS = is_null(static::$EXTRA_PARAMS) ? $inputs['PARAM'] : static::$EXTRA_PARAMS;
        }
        return null;
    }

}
