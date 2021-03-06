<?php

namespace Client;

use Exceptions\ClientExceptionResponse;

/**
 * Cliente Entrada CRUD events
 *
 * @author Administrador
 */
class ControllerEntrada extends AbstracClient {

    public function execute() {
        $model = new \Model\Entrada();
        $model->csvfile = ROOT . \Configs::getInstance()->app->csv;
        $post = $this->prepareArgs($model);
        if ($post['acao']) {
            $response = call_user_func_array([$model, $post['acao']], [$post]);
            if (!$response)
                throw new ClientExceptionResponse($model->error_msg);
        }
        printf('[{"code":"%s"}]', $response);
    }

    private function prepareArgs($model) {
        $post = filter_input_array(INPUT_POST, $model->args);
        if (!$post) {
            $post['id'] = $this->params[1];
            $post['acao'] = $this->params[0];
            $post['cliente'] = isset($this->params[2]) ? $this->params[2] : null;
        }
        return $post;
    }

    public function hasRequest() {
        return \Main::$Action === 'entrada';
    }

}
