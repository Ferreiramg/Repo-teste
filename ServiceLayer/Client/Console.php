<?php

namespace Client;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

/**
 * Description of Console
 *
 * @author Luis Paulo
 */
class Console extends AbstracClient {

    public function execute() {
        $cmd = sprintf("sudo %s", urldecode($this->params[0]));

    }

    public function hasRequest() {
        return \Main::$Action === 'console';
    }

}
