<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Client;

/**
 * Description of _Entrada
 *
 * @author Administrador
 */
class ControllerEntrada extends AbstracClient {

    public function execute() {
        $model = new \Model\Entrada();
        $post = filter_input_array(INPUT_POST, $model->args);
        if ($post['acao']) {
            $response = call_user_func_array([$model, $post['acao']], $post);
        }
    }

    public function hasRequest() {
        return \Main::$Action === 'entrada';
    }

}
