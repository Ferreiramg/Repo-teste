<?php

namespace Client;

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

        $response = call_user_func_array([$model, $p0], [$p1]);
        echo (string) json_encode($response);
    }

    public function hasRequest() {
        return \Main::$Action === 'silo';
    }

}
