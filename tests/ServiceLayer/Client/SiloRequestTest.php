<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

class SiloRequestTest extends PHPUnit {

    protected function setUp() {
        DBConnSqliteTest::ConnPDO();
    }

    protected function tearDown() {
        \Model\Cached\Memory::getInstance()->meminstance->delete('sile:totalEstocado2014');
    }

    public function testTotalChartRequest() {
        ob_start();
        $main = new Main();
        \Model\Cached\Memory::getInstance()->meminstance->delete('sile:totalEstocado2014');
        Main::$EXTRA_PARAMS = ['totalEstocado', '2014'];
        $main->run('GET', 'silo');
        $this->assertTrue(strlen(ob_get_contents()) > 25); //characters response
    }

}
