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
                        . '<tr><td colspan="3">Data: %s</td>'
                        . '<td colspan="3">Saida: %s</td></tr>',
                        $values['data'],round($values['saida'] / $mult, 2));
            } else {
                $string .= sprintf(
                        '<tr><td colspan="6"> </td></tr>'
                        . '<tr><td>Data: %s</td>'
                        . '<td colspan="2">Entrada: %s</td>'
                        . '<td>Saida: %s</td>'
                        . '<td>Umidade: %s</td>'
                        . '<td>Impureza: %s</td></tr>'
                        . '<tr><td colspan="3">Quebra de peso: %s</td>'
                        . '<td colspan="3">Impureza: %s</td></tr>'
                        , $values['data'], round($values['entrada'] / $mult, 2), round($values['saida'] / $mult, 2), $values['umidade'], $values['impureza'], round($values['q_pKg'] / $mult, 2), round($values['impKg'] / $mult, 2)
                );
            }
        }
        return $string;
    }

    public function drawHtml(array $data, $multiplicador = \Model\EntradaEntityIterator::KG_60) {
        extract($data['agregado']);
        $bruto = round($bruto / $multiplicador, 2);
        $qp = round($qp / $multiplicador, 2);
        $imp = round($imp / $multiplicador, 2);
        $servicos = round($servicos / $multiplicador, 2);
        $liquido = round($liquido / $multiplicador, 2);
        $liquido_descontos = round($bruto - $qp - $imp, 2);
        $saidas = round($saidas / $multiplicador, 2);
        $content = $this->content($data['content'], $multiplicador);
        return <<<HTML
        <table border="1">
            <head>
                <tr><td colspan="6"><h3>Relatório Resumido</h3></td></tr>
                <tr><td colspan="5">Nome: $nome</td><td>Dias : $dias</td></tr>
                <tr><td colspan="6">Taxa Armazenagem: $taxa% ao dia</td></tr>
                <tr><td colspan="6">Peso bruto: $bruto sacos $multiplicador Kg</td></tr>
                <tr><td colspan="6"><h4>Descontos</h4></td></tr>
                <tr>
                    <td colspan="2">Quebra de Peso: $qp</td>
                    <td colspan="2">Impureza: $imp</td>
                    <td colspan="2">Quebra tecnica: ???</td>
                </tr>
                <tr><td colspan="6">Peso Liquido Corrigido: $liquido_descontos</td></tr>
                <tr><td colspan="6"><h4>Prestação de Serviços</h4></td></tr>
                <tr><td colspan="6">Secagem e Limpeza: $servicos</td></tr>
                <tr><td colspan="6">Armazenagem: $armazenagem</td></tr>
                <tr><td colspan="6"><h4>Saldo</h4></td></tr>
                <tr><td colspan="6">Saldo Liquido: $liquido</td></tr>
                <tr><td colspan="6">Saida Total: $saidas</td></tr>
                <tr><td colspan="6">Saldo: $saldo</td></tr>
            </head>               
            <body>
                $content
            </body>
        </table>
HTML;
    }

}
