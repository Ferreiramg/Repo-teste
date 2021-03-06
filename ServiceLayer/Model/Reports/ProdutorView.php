<?php

namespace Model\Reports;

/**
 * Description of ProdutorView
 *
 * @author Luis
 */
class ProdutorView {

    private function content($data, $mult) {
        $string = "";
        foreach ($data as $values) {
            if ($values['saida'] > 0) {
                $string .= sprintf('<tr><td colspan="6"> </td></tr>'
                        . '<tr class="warning"><td>Data: %s</td>'
                        . '<td colspan="2">Saida: <b>%s</b></td>'
                        . '<td colspan="3"> <b>%s</b></td></tr>', $values['data'], round($values['saida'] / $mult, 2), $values['obs']);
            } else {
                $string .= sprintf(
                        '<tr><td colspan="6"> </td></tr>'
                        . '<tr><td>Data: %s</td>'
                        . '<td colspan="2">Entrada: %s</td>'
                        . '<td>Umidade: %s</td>'
                        . '<td colspan="2">Impureza: %s</td></tr>'
                        . '<tr><td colspan="3"> </td><td>Quebra de peso: %s</td>'
                        . '<td colspan="3">Impureza: %s</td></tr>'
                        , $values['data'], round($values['entrada'] / $mult, 2), $values['umidade'], $values['impureza'], round($values['q_pKg'] / $mult, 2), round($values['impKg'] / $mult, 2)
                );
            }
        }
        return $string;
    }

    private function negativeEfect($n) {
        return $n < 0 ? '<span class="text-danger">' . $n . '</span> <i><small>(Negativo)</small></i>' : $n;
    }

    public function drawHtml(array $data, $multiplicador = \Model\EntradaEntityIterator::KG_60) {
        extract($data['agregado']);
        $bruto = round($bruto / $multiplicador, 2);
        $qp = round($qp / $multiplicador, 2);
        $imp = round($imp / $multiplicador, 2);
        $servicos = round($servicos / $multiplicador, 2);
        $liquido = number_format(round($liquido / $multiplicador, 2),2) ;
        $liquido_descontos = number_format(round($bruto - $qp - $imp, 2), 2);
        $saidas = number_format(round($saidas / $multiplicador, 2), 2);
        $content = $this->content($data['content'], $multiplicador);
        $transf = round($transf / $multiplicador, 2);
        $saldo = round($saldo / $multiplicador, 2);
        $saldo = $this->negativeEfect($saldo - $armazenagem);
        $ano = \Model\Silo::getSessionYear();
        return <<<HTML
        <table class="table table-bordered table-responsive">
            <head>
                <tr><td colspan="6" class="well text-center"><h3>Relatório Resumido</h3></td></tr>
                <tr><td colspan="5">Nome: <h4>$nome</h4></td><td>Dias : $dias<p>Ano: $ano</p></td></tr>
                <tr><td colspan="6"><span class="glyphicon glyphicon-info-sign"></span><i class="text-info">Taxa Armazenagem: $taxa% ao dia</i></td></tr>
                <tr><td colspan="6">Peso bruto: <b>$bruto</b> sacos $multiplicador Kg</td></tr>
                <tr><td colspan="6" class="well"><h4>Descontos</h4></td></tr>
                <tr>
                    <td colspan="3">Quebra de Peso: <b>$qp</b></td>
                    <td colspan="3">Impureza: <b>$imp</b></td>
                </tr>
                <tr><td colspan="6">Peso Liquido Corrigido: <b>$liquido_descontos</b></td></tr>
                <tr><td colspan="6" class="well"><h4>Prestação de Serviços</h4></td></tr>
                <tr><td colspan="6">Secagem e Limpeza: <b>$servicos</b></td></tr>
                <tr><td colspan="6">Armazenagem: <b>$armazenagem</b></td></tr>
                <tr><td colspan="6" class="well"><h4>Saldo</h4></td></tr>
                <tr><td colspan="6">Transferências: <b>$transf</b></td></tr>
                <tr><td colspan="6">Saldo Liquido: <b class="text-success">$liquido</b> <i class="text-info">Peso liquido menos secagem mais transferência</i></td></tr>
                <tr><td colspan="6">Saida Total: <b>$saidas</b></td></tr>
                <tr><td colspan="6"><b>Saldo: $saldo</b> <i class="text-info">Com armazenamento já descontado</i></td></tr>
            </head>               
            <body class="page-break">
                        $content
            </body>
        </table>
HTML;
    }

}
