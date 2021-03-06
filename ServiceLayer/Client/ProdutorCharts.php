<?php

namespace Client;

use Model\Cached\Memory,
    Exceptions\ClientExceptionResponse;

/**
 * Description of ProdutorCharts
 *
 * @author Luis Paulo
 * @codeCoverageIgnore
 */
class ProdutorCharts extends AbstracClient {

    public function execute() {
        if ($this->params[0]) {
            $id = isset($this->params[1]) ? $this->params[1] : 1;
            $produtor = new \Model\Produtor($id - 1);
            $model = new \Model\ProdutorCharts($produtor);
            $_ = $this;
            $key = "report:" . $this->params[0] . $id;
            echo Memory::getInstance()->checkIn($key, function(\Memcached $mem)use ($key, $_, $model) {
                $response = call_user_func_array([$model, $_->params[0]], [$_->params]);
                if (!$response)
                    throw new ClientExceptionResponse($model->error_msg);
                $mem->set($key, json_encode((array) $response), CACHE_TIME);
                return json_encode((array) $response);
            });
        }
    }

    public function hasRequest() {
        return \Main::$Action === 'produtor_chart';
    }

}
