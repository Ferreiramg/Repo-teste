<?php

namespace Client;

use Model\Cached\Memory;

/**
 * Cliente only Read
 *
 * @author Luis Paulo
 */
class ProdutorReport extends AbstracClient {

    const CACHE_KEY = 'reportProdutor:';

    public function execute() {
        $report = new \Model\ProdutorReport();
        $view = new \Model\Reports\ProdutorView();      
        $m = isset($this->params[1]) && (int)$this->params[1] > 0 ? (int)$this->params[1] : 60;
        $key = (string)self::CACHE_KEY . $this->params[0].$m;
        $data = $report->resumeInfoEntradas((int) $this->params[0],$m);
        echo $view->drawHtml($data,$m);
        return null;
//        echo Memory::getInstance()->checkIn($key, function(\Memcached $mem)use ($report, $key, $view) {
//            $data = $report->resumeInfoEntradas((int) $this->params[0]);
//            $out = "Dados vazios, Relatorio não foi gerado!";
//            if (!empty($data))
//                $out = $view->drawHtml($data);
//            $mem->set($key, (string) $out, time() + 300);
//            return (string) $out;
//        });
    }

    public function hasRequest() {
        return \Main::$Action === 'produtor_report';
    }

}
