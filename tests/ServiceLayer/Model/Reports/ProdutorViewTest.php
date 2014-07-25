<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once dirname(dirname(__DIR__)) . '/DBConnSqliteTest.php';

/**
 * Description of ProdutorViewTest
 *
 * @author Luis Paulo
 */
class ProdutorViewTest extends PHPUnit {

    protected function setUp() {
        DBConnSqliteTest::ConnPDO(); //init connection sqlite db
    }

    public function testDrawhtml() {
        
    }

}
