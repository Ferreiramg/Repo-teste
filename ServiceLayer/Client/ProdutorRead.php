<?php

namespace Client;

use Model\Cached\Memory;

/**
 * Description of ProdutorRead
 *
 * @author Luis Paulo
 */
class ProdutorRead extends AbstracClient {

    public function execute() {
        $model = new \Model\Produtor();

        echo Memory::getInstance()->checkIn('produtor:', function($mem)use ($model) {
            $mem->set('produtor:', (string) $model, time() + 300);
            return (string) $model;
        });
    }

    public function hasRequest() {
        return \Main::$Action === 'produtor_read';
    }

}
