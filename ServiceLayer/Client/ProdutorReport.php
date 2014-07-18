<?php

namespace Client;

/**
 * Cliente only Read
 *
 * @author Luis Paulo
 */
class ProdutorReport extends AbstracClient {

    const CACHE_KEY = 'produtor:';

    public function execute() {
        $report = new \Model\ProdutorReport();
        $response = $report->resumeInfoEntradas((int) $this->params[0]);

        var_dump($response);
    }

    public function hasRequest() {
        return \Main::$Action === 'produtor_report';
    }

}
