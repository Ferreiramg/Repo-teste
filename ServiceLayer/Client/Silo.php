<?php

namespace Client;

use Model\Cached\Memory;

/**
 * Description of Silo
 *
 * @author Luis Paulo
 */
class Silo extends AbstracClient {

    public function execute() {
        $model = new \Model\Silo();

        $p1 = isset($this->params[1]) ? $this->params[1] : null;
        $p0 = isset($this->params[0]) ? $this->params[0] : 'totalEstocado';
        if ($p0 === 'changeYear') {
            $model->changeYear($p1);
            return json_encode([$p1]);
        }
        $key = $p0 . $p1 . \Model\Silo::getSessionYear();

        echo Memory::getInstance()->checkIn($key, function(\Memcached $mem)use ($key, $model, $p0, $p1) {
            $response = call_user_func_array([$model, $p0], [$p1]);
            $mem->set($key, (string) json_encode($response), CACHE_TIME);
            return (string) json_encode($response);
        });
    }

    public function hasRequest() {
        return \Main::$Action === 'silo';
    }

}
