<?php

namespace Client;

use Model\Cached\Memory,
    Exceptions\ClientExceptionResponse;

/**
 * Produtor Client actions CRUD event
 *
 * @author Luis Paulo
 */
class Produtor extends AbstracClient {

    public function execute() {
        $model = new \Model\Produtor();
        $post = $this->prepareArgs($model);
        if ($post['acao']) {
            $response = call_user_func_array([$model, $post['acao']], [$post]);
            if (!$response)
                throw new ClientExceptionResponse($model->error_msg);

            $this->clearCached();
            printf('[{"code":"%s"}]', $response);
        }
    }

    public function hasRequest() {
        return \Main::$Action === 'produtor';
    }

    /**
     * Clear memcached
     * @return bool
     */
    private function clearCached() {
        $cached = Memory::getInstance()->meminstance;
        return $cached->delete(ProdutorRead::CACHE_KEY);
    }

    /**
     * Apply filters
     * @return array 
     */
    private function prepareArgs() {
        $post = filter_input_array(INPUT_POST, [
            'id' => FILTER_VALIDATE_INT,
            'nome' => FILTER_SANITIZE_STRING,
            'grao' => FILTER_SANITIZE_STRING,
            'taxa' => FILTER_VALIDATE_FLOAT,
            'data' => date('now'), 'acao' => 0
        ]);
        if (!$post) {
            $post['id'] = $this->params[1];
            $post['acao'] = $this->params[0];
        }
        return $post;
    }

}
