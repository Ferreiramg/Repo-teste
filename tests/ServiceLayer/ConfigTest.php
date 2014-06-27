<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \PHPUnit_Framework_TestCase as PHPUnit;

/**
 * Description of ConfigTest
 *
 * @author luis Paulo
 */
class ConfigTest extends PHPUnit {

    public function testConfigSilgletonAccess() {
        $config = Configs::getInstance();
        $this->assertEquals($config === Configs::getInstance(), true);
    }

    public function testConfigGetData() {
        $p = Configs::getInstance()->get('app.port');
        $this->assertEquals($p, 80);
        $csv = Configs::getInstance()->app->csv;
        $this->assertEquals($csv, '/opt/tabela.csv');
        $this->assertTrue(is_string(Configs::getInstance()->getFile()));
    }

    public function testsetConfigCache() {
        \Configs::getInstance()->set('debug', true);

        $cf = Configs::getInstance()->get('debug');
        $this->assertTrue($cf);
    }

//      public function testRetrieveKeyFromUser()
//  {
//    $user = new User();
//
//    $reflectionOfUser = new ReflectionClass('User');
//    $method = $reflectionOfUser->getMethod('_getKey');
//    $method->setAccessible(true);
//
//    $this->assertEquals('mysuperawesomekey', $method->invokeArgs($user, array()));
//  }
}
