<?php

namespace Client;

use Exceptions\ClientExceptionResponse;

/**
 * Produtor Client actions CRUD event
 *
 * @author Luis Paulo
 */
class Produtor extends AbstracClient {

    public function execute() {
        $post = $this->prepareArgs();
        if ($post['acao']) {
            $model = new \Model\Produtor();
            $response = call_user_func_array([$model, $post['acao']], [$post]);
            if (!$response)
                throw new ClientExceptionResponse($model->error_msg);

            printf('[{"code":"%s"}]', $response);
        }
    }

    public function hasRequest() {
        return \Main::$Action === 'produtor';
    }

    /**
     * Apply filters
     * @return array 
     */
    private function prepareArgs() {
        $post = filter_input_array(INPUT_POST, [
            'id' => FILTER_VALIDATE_INT,
            'nome' => FILTER_SANITIZE_STRING,
            'email' => FILTER_SANITIZE_EMAIL,
            'grao' => FILTER_SANITIZE_STRING,
            'armazenagem' => FILTER_VALIDATE_FLOAT,
            'data' => 0, 'acao' => 0
        ]);
        if (!$post) {
            $post['id'] = $this->params[1];
            $post['acao'] = $this->params[0];
        }
        return $post;
    }

}
