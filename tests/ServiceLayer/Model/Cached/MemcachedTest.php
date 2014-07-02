<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;
use Model\Cached\Memory;

/**
 * Description of MemcachedTest
 *
 * @author Luis Paulo
 */
class MemcachedTest extends PHPUnit {

    public function testinstanceof() {
        $con = Memory::getInstance();
        $this->assertSame($con, Memory::getInstance());
    }

    public function testUsageMencached() {
        $_ = $this;
        $get = Memory::getInstance()
                ->checkIn('mykey', function($mem)use($_) {
            $_->assertInstanceOf('\Memcached', $mem);
            $mem->set('mykey', array());
            return array();
        });
        $get2 = Memory::getInstance()->checkIn('mykey'); // get direct memory

        $this->assertEquals($get2, array());
        $this->assertEquals($get, array());
        Memory::getInstance()->meminstance->delete('mykey');
        $false = Memory::getInstance()->meminstance->get('mykey');
        $this->assertFalse($false);
    }

}
