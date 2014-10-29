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
    }

    public function testRequestUsage() {
        $main = new Main();
        Main::$EXTRA_PARAMS = [25]; //ticket
        $main->run('GET', 'guardinclude');
        $this->expectOutputString('{"placa":"DBL4626","ticket":"000025","status":"F","peso_inicial":"20,510.0","peso_final":"56,020.0","peso_liguido":"35,510.0","data":["19\/04\/2013 07:32:03","19\/04\/2013 11:00:47","19\/04\/2013"],"emissor":"CLAUDIO BARBOSA DA CUNHA","motorista":"LUVERCI","observacao":"VENDA DE SOJA DE LUIS OTAVIO P  ADM"}');
    }

}
