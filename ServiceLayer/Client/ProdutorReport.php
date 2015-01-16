<?php

namespace Client;

use Model\Cached\Memory;

/**
 * Cliente only Read
 *
 * @author Luis Paulo
 */
class ProdutorReport extends AbstracClient {

    public function execute() {
        $report = new \Model\ProdutorReport();
        $view = new \Model\Reports\ProdutorView();
        $m = isset($this->params[1]) && (int) $this->params[1] > 0 ? (int) $this->params[1] : 60;
        $_ = $this;
        $key = "report::" . $_->params[0] . $m . \Model\Silo::getSessionYear();
        $data = Memory::getInstance()->checkIn($key, function(\Memcached $mem)use ($report, $key, $m, $_) {
            $dados = $report->resumeInfoEntradas((int) $_->params[0], $m);
            $mem->set($key, $dados, time() + 900);
            return $dados;
        });

        $out = "Dados vazios, Relatorio não foi gerado!";
        if (!empty($data))
            $out = $view->drawHtml($data, $m);

        echo (string) $out;
    }

    public function hasRequest() {
        return \Main::$Action === 'produtor_report';
    }

}
