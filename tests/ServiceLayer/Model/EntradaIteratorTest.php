<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;

include dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of EntradaIteratorTest
 *
 * @author Administrador
 */
class EntradaIteratorTest extends PHPUnit {

    protected $object, $stmt;

    protected function setUp() {
        $this->object = new Model\EntradaEntityIterator();
        $sql = "SELECT id,data as dia,SUM(saida_peso)as saida, SUM(peso) as entrada "
                . "FROM entradas WHERE _cliente = :id GROUP BY data";
        $conn = DBConnSqliteTest::initConn();
        $stm = $conn->prepare($sql);

        $stm->bindValue(':id', 1); //ID Cliente
        $this->object = new Model\EntradaEntityIterator();
        $stm->setFetchMode(PDO::FETCH_INTO, $this->object);
        $stm->execute();
        $this->stmt = $stm;
    }

    private function makeCalendar() {
        $hoje = new DateTime('now');
        $entrada = new DateTime('01-05-2014');
        $i = 0;
        while ($entrada < $hoje) {
            $deduction = $this->object->deduction();
            $this->object->append(array(
                'dia' => $entrada->format('Y-m-d'),
                'entrada' => 0,
                'desconto' => $deduction,
                'saldo' => $this->object->getSaldo($deduction)));
            foreach ($this->stmt as $ob) {
                
            }
            $entrada = $entrada->modify('+1day');
            ++$i;
        }
        return $ob;
    }

    private function deduction($saldo) {
        $taxa = (float) 0.033 / 100.0;
        return $saldo < 0 ? 0 :
                round($taxa * $saldo, 2, PHP_ROUND_HALF_UP);
    }

    public function testIteratorWithFetchMode() {

        $ob = $this->makeCalendar();
        
        $this->assertEquals($this->object->offsetGet(7)['dia'], '2014-05-08');
        $this->assertEquals($this->object->offsetGet(3)['saldo'], 0);//
        $this->assertEquals($this->object->offsetGet(4)['saldo'], 0);//
        $this->assertEquals($saldo = $this->object->offsetGet(5)['saldo'], round(13000 / 60, 2));
        $this->assertEquals($this->object->offsetGet(6)['desconto'], $this->deduction($saldo));
        $this->assertEquals(round(13000 / 60, 2), $ob->entrada);
    }

}
