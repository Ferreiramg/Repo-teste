<?php

namespace Client;

use Exceptions\ClientExceptionResponse;

/**
 * Description of ProdutorCharts
 *
 * @author Luis Paulo
 */
class ProdutorCharts extends AbstracClient {

    public function execute() {
        if ($this->params[0]) {
            $id = isset($this->params[1]) ? $this->params[1] : 1;
            $produtor = new \Model\Produtor($id - 1);
            $model = new \Model\ProdutorCharts($produtor);
            $response = call_user_func_array([$model, $this->params[0]], [$this->params]);
            if (!$response)
                throw new ClientExceptionResponse($model->error_msg);
            
            echo json_encode((array)$response);
        }
    }

    public function hasRequest() {
        return \Main::$Action === 'produtor_chart';
    }

}
