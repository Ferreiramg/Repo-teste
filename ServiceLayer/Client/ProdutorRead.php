<?php

namespace Client;

use Model\Cached\Memory;

/**
 * Cliente only Read
 *
 * @author Luis Paulo
 */
class ProdutorRead extends AbstracClient {

    const CACHE_KEY = 'produtor:';

    public function execute() {
        $model = new \Model\Produtor();

        echo Memory::getInstance()->checkIn('produtor:', function(\Memcached $mem)use ($model) {
            $mem->set('produtor:', (string) $model, time() + 300);
            return (string) $model;
        });
    }

    public function hasRequest() {
        return \Main::$Action === 'produtor_read';
    }

}
