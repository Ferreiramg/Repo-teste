<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

/**
 * Description of GItegrationTest
 *
 * @author Luis
 */
class GItegrationRequestTest extends PHPUnit {

    protected $obj;

    public function setUp() {
        $this->obj = new \Client\IntegrationGuardian();
        $this->makeCSVData();
        \Configs::getInstance()->set('connection.odbc.export.csv', '/opt/data.csv');
    }

    public function testRequestUsage() {
        $main = new Main();
        Main::$EXTRA_PARAMS = [2221]; //ticket
        $main->run('GET', 'guardinclude');
        $this->expectOutputString('{"placa":"GLX1619","ticket":"002221","status":"F","peso_inicial":7890,"peso_final":21870,"peso_liguido":13980,"data":["28\/01\/2015 08:05:18","28\/01\/2015 09:01:26","28\/01\/2015"],"emissor":"Luis Paulo F.","motorista":"ROGERIO","observacao":"RETIRADA MILHO PAULO LOPES - COOPERATIVA","conflito":{"has":false,"dados":[]}}');
    }

    private function makeCSVData() {
        if (file_exists(ROOT . '/opt/data.csv')) {
            return null;
        }
        file_put_contents(ROOT . '/opt/data.csv', "2221,2226,002221,GLX1619,GLX1619,S,F,,,003,001,E,1132,Pesagem Final OK,Expedição,,RETIRADA MILHO PAULO LOPES - COOPERATIVA,Luis Paulo F.,MILHO,7.89000000e+03,002,01/28/15 08:05:18,Balança,2.18700000e+04,002,01/28/15 09:01:26,Balança,1.39800000e+04,1.00000000e+00,1.00000000e+00,1.39800000e+04,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,0,,,0:56,ROGERIO,,,,,,,,,,0.00000000e+00,K,K,,,1,,,,,,0.00000000e+00,0,,,,,,,-1,,-1,-1,
2222,2227,002222,TRT2015,TRT2015,S,F,,,003,001,E,1134,Pesagem Final OK,Expedição,,PESAGEM POLPA PARA ANA FLAVIA,Luis Paulo F.,POLPA CITRICA,1.02300000e+04,002,01/28/15 16:25:19,Balança,6.49000000e+03,002,01/29/15 07:00:00,Balança,3.74000000e+03,1.00000000e+00,1.00000000e+00,3.74000000e+03,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,0,,,1:00,ALISSON,,,,,,,,,,0.00000000e+00,K,k,,,2,,,,,,0.00000000e+00,0,,,,,,,-1,,-1,-1,
2223,2228,002223,ALS2015,ALS2015,S,F,,,,001,E,1133,Pesagem Final OK,Expedição,,RETIRADA DE MILHO ESTANCIA PJ,EZEQUIEL,MILHO,6.49000000e+03,002,01/29/15 07:28:27,Balança,1.04900000e+04,002,01/29/15 08:05:27,Balança,4.00000000e+03,1.00000000e+00,1.00000000e+00,4.00000000e+03,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,0,,,0:36,ALISSON,,,,,,,,,,0.00000000e+00,K,K,,,1,,,,,,0.00000000e+00,0,,,,,,,-1,,-1,-1,
2224,2229,002224,GVH7256,GVH7256,S,F,,,,003,R,1072,Pesagem Final OK,Recebimento,,POLPA CITRICA ,EZEQUIEL,POLPA  CÍTRICA,4.34900000e+04,002,01/29/15 07:34:13,Balança,1.64500000e+04,002,01/29/15 08:11:53,Balança,2.70400000e+04,1.00000000e+00,1.00000000e+00,2.70400000e+04,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,0,,,0:37,GERALDO MEDEIROS,,,,,,,,,,0.00000000e+00,K,K,,,2,,,,,,0.00000000e+00,0,,,,,,,-1,,-1,-1,
2215,2220,002215,MRF3741,MRF3741,S,F,,,003,003,E,1127,Pesagem Final,Expedição,,PESAGEM DE POLPA CITRICA SILO,Luis Paulo Silva Ferreira,POLPA CÍTRICA,4.04200000e+04,002,01/24/15 09:00:00,,1.71800000e+04,002,01/24/15 09:20:00,Balança,2.32400000e+04,1.00000000e+00,1.00000000e+00,2.32400000e+04,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,0,,,0:20,,,,,,,,,,,0.00000000e+00,,K,0,,2,,,,,,0.00000000e+00,1,2,3,4,5.00000000e+00,6.00000000e+00,7.00000000e+00,-1,,-1,-1,
2216,2221,002216,GLX4154,GLX4154,S,F,,,003,001,E,1127,Pesagem Final OK,Expedição,,RETIRADA DE MILHO PARA FERNANDO VILELA,Luis Paulo F.,MILHO,5.20000000e+03,002,01/26/15 15:34:48,Balança,1.11000000e+04,002,01/26/15 16:03:23,Balança,5.90000000e+03,1.00000000e+00,1.00000000e+00,5.90000000e+03,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,0,,,0:28,CARLOS,,,,,,,,,,0.00000000e+00,K,K,,,1,,,,,,0.00000000e+00,0,,,,,,,-1,,-1,-1,
2217,2222,002217,CDM0001,CDM0001,S,F,,,003,004,E,1128,Pesagem Final OK,Expedição,,PESAGEM DE GADO,Luis Paulo F.,GADO,3.94000000e+03,002,01/26/15 15:51:50,Balança,4.54000000e+03,002,01/26/15 16:23:44,Balança,6.00000000e+02,1.00000000e+00,1.00000000e+00,6.00000000e+02,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,0,,,0:31,REGIS,,,,,,,,,,0.00000000e+00,K,K,,,1,,,,,,0.00000000e+00,0,,,,,,,-1,,-1,-1,
2218,2223,002218,GKM7651,GKM7651,S,F,,,003,001,E,1129,Pesagem Final OK,Expedição,,RETIRADA MILHO JULIANO ASSUITI - JOÃO CLAUDINO,Luis Paulo F.,MILHO,9.42000000e+03,002,01/27/15 08:09:02,Balança,2.00100000e+04,002,01/27/15 08:52:08,Balança,1.05900000e+04,1.00000000e+00,1.00000000e+00,1.05900000e+04,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,0,,,0:43,SUED,,,,,,,,,,0.00000000e+00,K,K,,,1,,,,,,0.00000000e+00,0,,,,,,,-1,,-1,-1,
2219,2224,002219,GLX1619,GLX1619,S,F,,,003,001,E,1130,Pesagem Final OK,Expedição,,RETIRADA MILHO PAULO LOPES - COOPERATIVA,Luis Paulo F.,MILHO,7.93000000e+03,002,01/27/15 10:53:17,Balança,2.19000000e+04,002,01/27/15 12:36:24,Balança,1.39700000e+04,1.00000000e+00,1.00000000e+00,1.39700000e+04,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,0,,,1:43,JOÃO BATISTA,,,,,,,,,,0.00000000e+00,K,K,,,1,,,,,,0.00000000e+00,0,,,,,,,-1,,-1,-1,
2220,2225,002220,GLX4154,GLX4154,S,F,,,003,001,E,1131,Pesagem Final OK,Expedição,,RETIRADA MILHO PARA FERNANDO VILELA,Luis Paulo F.,MILHO,5.14000000e+03,002,01/27/15 15:09:20,Balança,1.11900000e+04,002,01/27/15 15:35:49,Balança,6.05000000e+03,1.00000000e+00,1.00000000e+00,6.05000000e+03,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,0,,,0:26,CARLOS,,,,,,,,,,0.00000000e+00,K,K,,,1,,,,,,0.00000000e+00,0,,,,,,,-1,,-1,-1,
2214,2219,002214,DLR0001,DLR0001,S,F,,,003,003,E,1126,Pesagem Final OK,Expedição,,RETIRADA POLPA CITRICA DANIEL LIMA,Luis Paulo Silva Ferreira,POLPA  CÍTRICA,6.61000000e+03,002,01/26/15 08:09:13,,1.09300000e+04,002,01/26/15 08:21:35,Balança,4.32000000e+03,1.00000000e+00,1.00000000e+00,4.32000000e+03,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,0,,,0:12,DANIEL,,,,,,,,,,0.00000000e+00,,K,0,,1,,,,,,0.00000000e+00,1,,,,,,,-1,,-1,-1,
");
    }

}
