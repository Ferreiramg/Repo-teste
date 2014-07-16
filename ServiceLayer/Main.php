<?php

/**
 * Main Bootstrap of Application
 *
 * @author Luis Paulo
 */
class Main {

    public static $REQUEST = null;
    public static $Action = null;
    public static $EXTRA_PARAMS = null;

    /**
     * Run Application
     * @param string $request
     * @param string $action
     */
    public function run($request, $action = null) {
        static::$REQUEST = $request;
        $this->urlRewrite($action);

        $event = new Event\Delegate();
        $manager = new Event\ObsManager();
        $client = new Client\Delegate($manager);

        $event->addEvent(new Event\RequestGet($client))
                ->addEvent(new Event\RequestPost($client))
                ->addEvent(new Event\RequestDelete($client));
        $event->runEvents();
        $manager->notify();
    }

    /**
     * Prepare .htaccess params to usage
     * @param string $custom
     * @return null
     */
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
