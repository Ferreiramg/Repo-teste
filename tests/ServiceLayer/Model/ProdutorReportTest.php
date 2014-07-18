<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of ProdutorReportTest
 *
 * @author Luis Paulo
 */
class ProdutorReportTest extends PHPUnit {

    protected $prod;

    protected function setUp() {
        DBConnSqliteTest::ConnPDO(); //init connection sqlite db
        $this->prod = new Model\ProdutorReport();
    }

    public function testResumeInfoProdutor() {
        $this->assertCount(3, $this->prod->resumeInfoEntradas());
    }

    /**
     * @expectedException Exceptions\ClientExceptionResponse 
     */
    public function testExpectedExceptionError() {
        $this->prod->resumeInfoEntradas(56); //invalid id
    }

}
