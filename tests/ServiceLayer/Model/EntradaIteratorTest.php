<?php

use \PHPUnit_Framework_TestCase as PHPUnit;

require_once dirname(__DIR__) . '/DBConnSqliteTest.php';

/**
 * Description of EntradaIteratorTest
 *
 * @author Administrador
 */
class EntradaIteratorTest extends PHPUnit {

    protected $object, $stmt;

    protected function setUp() {
        $this->stmt = DBConnSqliteTest::ConnPDO();
        $mock = $this->getMock('\Model\Produtor', ['getTaxa']);
        $mock->expects($this->any())
                ->method('getTaxa')
                ->will($this->returnValue(0.033));
        $this->object = new Model\EntradaEntityIterator(60);
        $this->object->setCliente($mock);
        $this->object->rewind();
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
                'saida' => 0,
                'desconto' => $deduction,
                'saldo' => $this->object->getSaldo($deduction),
                'observacao' => '',
                'decorrido' => ++$i,
            ]);
            $entrada = $entrada->modify('+1day');
        }
    }

    private function deduction($saldo) {
        $taxa = (float) 0.033 / 100.0;
        return $saldo < 0 ? 0 :
                round($taxa * $saldo, 2, PHP_ROUND_HALF_UP);
    }

    public function testIteratorWithFetchMode() {
        $this->object->setCols($data = $this->getData(1));
        $this->makeCalendar();
        $this->assertEquals($this->object->offsetGet(7)['dia'], '2014-05-08');
        $this->assertEquals($this->object->offsetGet(28)['dia'], '2014-05-29');
        $this->assertEquals($this->object->offsetGet(34)['dia'], '2014-06-04');
    }

}
