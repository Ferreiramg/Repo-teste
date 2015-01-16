<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of EntradaDataReadTest
 *
 * @author Luis Paulo
 */
class EntradasReadDataTest extends PHPUnit {

    protected function setUp() {
        DBConnSqliteTest::ConnPDO();
    }

    public function testReadData() {
        $entrada = new Model\EntradasReadData();
        $this->assertTrue(is_array($data = $entrada->getdataByClientId(1, '2015')));
        $this->assertTrue(count($data) > 1);
        $this->assertTrue(count($entrada->getdataByClientId(100, '2015')) === 0);
    }

    public function testMakeCalendar() {
        Main::$EXTRA_PARAMS = array(true, 1);
        $entrada = new \Client\EntradaRead();

        $reflection = new ReflectionClass('\Client\EntradaRead');
        $method = $reflection->getMethod('calendarData');
        $method->setAccessible(true);
        $invo = $method->invokeArgs($entrada, array(60, date('Y')));
        $date1 = new DateTime("2014-05-06");
        $date2 = new DateTime("now");

        $diff = $date2->diff($date1)->format("%a") + 1; //number of days
        $this->assertEquals(count($invo), $diff);
    }

    public function testMakeCalendarEmptyData() {
        Main::$EXTRA_PARAMS = array(true, 3);
        $entrada = new \Client\EntradaRead();

        $reflection = new ReflectionClass('\Client\EntradaRead');
        $method = $reflection->getMethod('calendarData');
        $method->setAccessible(true);
        $invo = $method->invokeArgs($entrada, array(60, date('Y')));
        $this->assertTrue(empty($invo));
    }

}
