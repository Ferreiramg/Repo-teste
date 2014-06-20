<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once  dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of EntradaIteratorTest
 *
 * @author Administrador
 */
class EntradaIteratorTest extends PHPUnit {

    protected $object, $stmt;

    protected function setUp() {
        $mock = $this->getMock('Cliente', ['getArmazenagem']);
        $mock->expects($this->any())
                ->method('getArmazenagem')
                ->will($this->returnValue(0.033));
        $this->object = new Model\EntradaEntityIterator(60);
        $this->object->setCliente($mock);
        $this->stmt = DBConnSqliteTest::ConnPDO();
    }

    private function getData($id) {
        $sql = "SELECT entradas.*, SUM(saida_peso)as saida, SUM(peso) as entrada, SUM(peso_corrigido) as corrigido FROM entradas WHERE _cliente = :id GROUP BY data ORDER BY data ASC";
        $stm = $this->stmt->prepare($sql);
        $stm->bindValue(':id', $id);
        if ($stm->execute()) {
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        }
    }

    private function makeCalendar() {
        $hoje = new DateTime('now');
        $entrada = new DateTime('01-05-2014');
        $i = 0;
        while ($entrada < $hoje) {
            $deduction = $this->object->deduction();
            $this->object->append([
                'dia' => $entrada->format('Y-m-d'),
                'entrada' => 0,
                'desconto' => $deduction,
                'saldo' => $this->object->getSaldo($deduction)
            ]);
            $entrada = $entrada->modify('+1day');
            ++$i;
        }
    }

    private function deduction($saldo) {
        $taxa = (float) 0.033 / 100.0;
        return $saldo < 0 ? 0 :
                round($taxa * $saldo, 2, PHP_ROUND_HALF_UP);
    }

    public function testIteratorWithFetchMode() {

        $this->object->setCols($this->getData(1));
        $this->makeCalendar();

        $this->assertEquals($this->object->offsetGet(7)['dia'], '2014-05-08');
        $this->assertEquals($this->object->offsetGet(3)['saldo'], 0); //não tem entrada
        $this->assertEquals($this->object->offsetGet(4)['saldo'], 0); //não tem entrada
        $this->assertEquals($saldo = $this->object->offsetGet(5)['saldo'], round(11930 / 60, 2));
        $this->assertEquals($this->object->offsetGet(6)['desconto'], $this->deduction($saldo));

        $this->assertEquals($this->object->offsetGet(28)['dia'], '2014-05-29');
        $this->assertEquals((int)$this->object->offsetGet(28)['saldo'], 396);
    }

}
