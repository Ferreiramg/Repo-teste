<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Model\Cached;

/**
 * Description of Memory
 *
 * @author Luis Paulo
 */
class Memory {

    protected $meninstance;

    public function init() {
        $this->meminstance = new \Memcache();
        $this->meminstance->pconnect('localhost', 11211);
    }

    public function on() {
        return $this->meninstance;
    }

use \ConfigTrait;
}
