<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Client;

use ElephantIO\Client as Elephant,
    ElephantIO\Engine\SocketIO\Version1X;

/**
 * Description of Console
 *
 * @author Laticinios PJ
 */
class Console extends AbstracClient {


    public function execute() {
        $cmd = sprintf("sudo %s", urldecode($this->params[0]));
        $elephant = new Elephant(new Version1X('http://localhost:3000'));
        $elephant->initialize();
//        $pipes = [];
//        $descriptorspec = array(
//            0 => array("pipe", "r"), // stdin is a pipe that the child will read from
//            1 => array("pipe", "w"), // stdout is a pipe that the child will write to
//            2 => array("pipe", "w")    // stderr is a pipe that the child will write to
//        );
//        flush();
//        $process = proc_open($cmd, $descriptorspec, $pipes, realpath('./'), array());
//        if (is_resource($process)) {
//            while ($s = fgets($pipes[1])) {
//                $elephant->emit('message', [print_r($s, true)]);
//                flush();
//            }
//        }
        $elephant->emit('message', ['ok']);
        $elephant->close();
    }

    public function hasRequest() {
        return \Main::$Action === 'console';
    }

}
