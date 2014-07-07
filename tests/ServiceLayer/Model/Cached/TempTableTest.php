<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once dirname(dirname(__DIR__)) . '/DBConnSqliteTest.php';

/**
 * Description of TempTableTest
 *
 * @author Luis Paulo
 */
class TempTableTest extends PHPUnit {

    private $tmpt;

    protected function setUp() {
        $this->tmpt = new \Model\Cached\TempTable(DBConnSqliteTest::ConnPDO());
    }

    protected function tearDown() {
        \Model\Cached\TempTable::drop();
    }

    public function testPropertiesSetAndGet() {
        $tmpt = $this->tmpt;
        $tmpt->id = 1;
        $tmpt->produtor = "Luis Paulo";
        $tmpt->saldo = 97450;
        $tmpt->impureza_media = 1.56;
        $tmpt->umidade_media = 13;
        $tmpt->service_secagem = 15000;
        $tmpt->quebra_peso = 7540;
        $tmpt->quebra_tecnica = 542;
        $tmpt->service_armazenagem = 2500;

        $this->assertEquals($tmpt->count(), 0);
        $this->assertTrue($tmpt->save($stmt));
        $this->assertEquals($stmt->getSQL(), $this->expected);

        $tmpt->reload();
        $this->assertEquals($tmpt->count(), 1);
        $this->assertEquals($tmpt->saldo, '97450');
    }

    private $expected = "INSERT INTO tmpReport (id,produtor,saldo,impureza_media,service_secagem,quebra_peso, umidade_media, quebra_tecnica,service_armazenagem)VALUES('1','Luis Paulo','97450','1.56','15000','7540','13', '542','2500')";

}
