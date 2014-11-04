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
        $this->expectOutputString('{"placa":"DDI6189","ticket":"000025","status":"F","peso_inicial":4580,"peso_final":6850,"peso_liguido":2270,"data":["29\/04\/2013 08:47:51","29\/04\/2013 10:17:12","29\/04\/2013"],"emissor":"CLAUDIO BARBOSA DA CUNHA","motorista":"ZOSE CARLOS","observacao":"CREME DE LEITE LAT PJ \/PARA RITAPOLIS","conflito":{"has":false,"dados":[]}}');
    }

}
