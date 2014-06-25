<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;
use GuzzleHttp\Client;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of EntradasRequestUsageTest
 *
 * @author Luis Paulo
 */
class EntradasRequestUsageTest extends PHPUnit {

    protected $requestServer;

    protected function setUp() {
        $this->requestServer = new Client();

        DBConnSqliteTest::ConnPROPEL();
    }

    public function testEventPostRequestMain() {

    }

}
