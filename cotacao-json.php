<?php
$COTACAO = 'http://www2.bmf.com.br/pages/portal/bmfbovespa/graficoshome/includeGraficosBMFBovespa2011_pt-br.asp?Acao=BUSCA&Mercadoria=CCM';
$content = file_get_contents($COTACAO);
if ($content) {
    $first_step = explode('<div id="graficoDerivativosInterno">', $content);
    $second_step = explode("</div>", $first_step[1]);
    $dom = new \DOMDocument();
    $dom->loadHTML(trim($second_step[0]));

    $data = '[{"img":"/%s","preco":"%s","data":"%s","oc":"%s"}]';
    printf(str_replace('//', '\/', $data), $dom->getElementsByTagName("img")->item(0)->getAttribute('src'), trim(strip_tags(stristr($second_step[1], ':'))), $dom->getElementsByTagName("p")->item(0)->nodeValue, $dom->getElementsByTagName("span")->item(2)->nodeValue);
}