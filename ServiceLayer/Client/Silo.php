<?php

namespace Client;

use Model\Cached\Memory;

/**
 * Description of Silo
 *
 * @author Luis Paulo
 */
class Silo extends AbstracClient {

    const S_KEY = "silo:";

    public function execute() {
        $model = new \Model\Silo();
        $p1 = isset($this->params[1]) ? $this->params[1] : null;
        $p0 = isset($this->params[0]) ? $this->params[0] : 'totalEstocado';
        $key = (string) self::S_KEY . $p0 . $p1;
        echo Memory::getInstance()
                ->checkIn($key, function(\Memcached $mem)use ($model, $p0, $p1, $key) {
                    $response = call_user_func_array([$model, $p0], [$p1]);
                    $response = (string) json_encode($response);
                    $mem->set($key, $response, time() + 300);
                    return $response;
                });
    }

    public function hasRequest() {
        return \Main::$Action === 'silo';
    }

}
