<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

/**
 * Description of GItegrationTest
 *
 * @author Luis
 */
class GItegrationTest extends PHPUnit {

    protected $obj;
    public function setUp(){
        \Configs::getInstance()->set('debug', true);
        $this->obj = new \Model\IntegrationGuardian();
    }

    public function testFormatTicktForGuardian() {
        $tick = 26;
        $res = \Model\Entrada::TicketFormat($tick);
        $this->assertEquals($res, "000026");
        $this->assertEquals(\Model\Entrada::TicketFormat(1115), "001115");
    }

    public function testFilterTicketAccess() {

        $data = $this->obj->filterTicket(\Model\Entrada::TicketFormat(25));
        $this->assertEquals($data['ticket'], "000025");
    }
    /**
     * @expectedException \Exceptions\ClientExceptionResponse
     */
    public function testFilterException(){
        $this->obj->filterTicket(66666);
    }

}
