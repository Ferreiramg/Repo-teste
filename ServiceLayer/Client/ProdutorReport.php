<?php

namespace Client;

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
        $p2 = isset($this->params[2]) ? $this->params[2] : null;

        $data = $report->resumeInfoEntradas((int) $this->params[0], $m, $p2);
        $out = "Dados vazios, Relatorio não foi gerado!";
        if (!empty($data))
            $out = $view->drawHtml($data, $m);

        echo (string) $out;
    }

    public function hasRequest() {
        return \Main::$Action === 'produtor_report';
    }

}
