<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Client;

/**
 * Description of SiloActions
 *
 * @author Luis Paulo
 * @codeCoverageIgnore
 */
class SiloActions extends AbstracClient {

    public function execute() {
        $post = $this->params();
        if ($post['acao']) {
            $model = new \Model\Silo();
            $response = call_user_func_array([$model, $post['acao']], [$post]);
            if (!$response)
                throw new ClientExceptionResponse($model->error_msg);

            echo json_encode($response);
        }
    }

    public function hasRequest() {
        return \Main::$Action === 'simulator';
    }

    private function params() {
        return filter_input_array(INPUT_POST, [
            'peso' => FILTER_VALIDATE_INT,
            'dias' => FILTER_VALIDATE_INT,
            'umidade' => FILTER_VALIDATE_FLOAT,
            'taxa' => FILTER_VALIDATE_FLOAT,
            'impureza' => FILTER_VALIDATE_FLOAT,
            'acao' => false
        ]);
    }

}
