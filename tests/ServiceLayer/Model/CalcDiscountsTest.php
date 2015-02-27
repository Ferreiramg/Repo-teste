<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

/**
 * Description of CalcDiscountsTest
 *
 * @author Administrador
 */
class CalcDiscountsTest extends PHPUnit {

    protected $tmp;

    public function setUp() {
        $tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        file_put_contents($this->tmp = $tmp . 'tmp_table.csv', $this->data);
    }

    public function tearDown() {
        $tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        @unlink($tmp . 'tmp_table.csv');
    }

    public function testQuebraTecnica() {
        $qp = round((0.30 / 100.0) * (25000), 2, PHP_ROUND_HALF_UP);
        $this->assertEquals(Model\CalcDiscounts::quebraTecnica(25000), $qp);
    }

    public function testQuebraImpureza() {
        $qi = round((1.0 * 25000) / 100.0, 2, PHP_ROUND_HALF_UP);
        $this->assertEquals(Model\CalcDiscounts::impureza(1.0, 25000), $qi);
    }

    public function testQuebradePeso() {
        $umidade = 20.6;
        $csv = new Model\CSV($this->tmp, ';');
        $calc = new Model\CalcDiscounts(
                new Model\CSVFilter($csv, $umidade), $umidade);
        $this->assertEquals($calc->quebraPeso(), 9.01 / 100.0);
    }

    public function testQuebraSecagem() {
        $umidade = 17.0;
        $csv = new Model\CSV($this->tmp, ';');
        $calc = new Model\CalcDiscounts(
                new Model\CSVFilter($csv, $umidade), $umidade);
        $this->assertEquals($calc->servicoSecagem(), 3.93 / 100.0);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage 14.3 , não é multiplo de dois
     */
    public function testGetExceptionUmidadeWrongValue() {
        $umidade = 14.3; //errado.
        $csv = new Model\CSV($this->tmp, ';');
        $calc = new Model\CalcDiscounts(
                new Model\CSVFilter($csv, $umidade), $umidade);
        $calc->servicoSecagem();
    }

    public function testCurrentData() {
        $umidade = 20.2;
        $csv = new Model\CSV($this->tmp, ';');
        $calc = new Model\CalcDiscounts(
                new Model\CSVFilter($csv, $umidade), $umidade);
        $calc->servicoSecagem();
        $this->assertEquals($calc->current(), ['20.20', '4.69', '8.53', '13.22']);
    }

    private $data = "13.00;1.75;0.00;1.75
13.20;1.75;0.13;1.88
13.40;1.75;0.37;2.12
13.60;1.75;0.61;2.36
13.80;1.75;0.85;2.60
14.00;1.75;1.09;2.84
14.20;1.75;1.33;3.08
14.40;1.75;1.57;3.32
14.60;3.38;1.81;5.19
14.80;3.43;2.05;5.48
15.00;3.45;2.29;5.74
15.20;3.53;2.53;6.07
15.40;3.63;2.77;6.40
15.60;3.67;3.01;6.68
15.80;3.74;3.25;6.99
16.00;3.74;3.49;7.23
16.20;3.81;3.73;7.54
16.40;3.84;3.97;7.81
16.60;3.86;4.21;8.07
16.80;3.88;4.45;8.33
17.00;3.93;4.69;8.63
17.20;4.15;4.93;9.08
17.40;4.24;5.17;9.41
17.60;4.06;5.41;9.47
17.80;4.14;5.65;9.79
18.00;4.18;5.89;10.07
18.20;4.19;6.13;10.32
18.40;4.28;6.37;10.65
18.60;4.29;6.61;10.90
18.80;4.39;6.85;11.24
19.00;4.39;7.09;11.48
19.20;4.41;7.33;11.75
19.40;4.49;7.57;12.06
19.60;4.52;7.81;12.34
19.80;4.59;8.05;12.64
20.00;4.64;8.29;12.93
20.20;4.69;8.53;13.22
20.40;4.74;8.77;13.51
20.60;4.76;9.01;13.77
20.80;4.82;9.25;14.08
21.00;4.83;9.49;14.33
21.20;5.00;9.73;14.73
21.40;5.00;9.97;14.97
21.60;5.10;10.21;15.31
21.80;5.14;10.45;15.59
22.00;5.30;10.69;15.99
22.20;5.34;10.93;16.27
22.40;5.49;11.17;16.66
22.60;5.60;11.41;17.01
22.80;5.80;11.65;17.45
23.00;5.95;11.89;17.84
23.20;5.98;12.13;18.11
23.40;6.20;12.37;18.57
23.60;6.31;12.61;18.92
23.80;6.45;12.85;19.30
24.00;6.50;13.09;19.60
24.20;6.63;13.33;19.96
24.40;6.74;13.57;20.31
24.60;6.87;13.81;20.68
24.80;7.00;14.05;21.05
25.00;7.12;14.29;21.42
25.20;7.25;14.53;21.78
25.40;7.39;14.77;22.16
25.60;7.52;15.01;22.54
25.80;7.67;15.25;22.92
26.00;7.79;15.49;23.28
26.20;7.95;15.73;23.68
26.40;8.10;15.97;24.07
26.60;8.25;16.21;24.46
26.80;8.41;16.45;24.86
27.00;8.57;16.69;25.26
27.20;8.76;16.93;25.69
27.40;8.93;17.17;26.10
27.60;9.11;17.41;26.53
27.80;9.30;17.65;26.95
28.00;9.48;17.89;27.37
28.20;9.69;18.13;27.82
28.40;9.89;18.37;28.26
28.60;10.10;18.61;28.71
28.80;10.31;18.85;29.16
29.00;10.52;19.09;29.62
29.20;10.79;19.33;30.12
29.40;11.05;19.57;30.62
29.60;11.32;19.81;31.14
29.80;11.59;20.05;31.64
30.00;11.86;20.29;32.15";

}
