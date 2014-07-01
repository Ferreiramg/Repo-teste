<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Client;

use Exceptions\ClientExceptionResponse;

/**
 * Description of _Entrada
 *
 * @author Administrador
 */
class ControllerEntrada extends AbstracClient {

    public function execute() {
        $model = new \Model\Entrada();
        $post = $this->postArgs($model);
        if ($post['acao']) {
            $response = call_user_func_array([$model, $post['acao']], [$post]);
            if (!$response)
                throw new ClientExceptionResponse($model->error_msg);
        }
    }

    public function postArgs($model) {
        $post = filter_input_array(INPUT_POST, $model->args);
        if (!$post) {
            $post['id'] = $this->params[1];
            $post['acao'] = $this->params[0];
        }
        return $post;
    }

    public function hasRequest() {
        return \Main::$Action === 'entrada';
    }

}
