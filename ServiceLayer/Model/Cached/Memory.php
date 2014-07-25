<?php

namespace Model\Cached;

/**
 * Description of Memory
 *
 * @author Luis Paulo
 */
class Memory {

    public $meminstance;

    public function init() {
        $this->meminstance = new \Memcached();
        $this->meminstance->addServer('localhost', 11211);
        $this->meminstance;
    }

    public function checkIn($key, \Closure $callback = null) {
        if (!($output = $this->meminstance->get($key))) {
            if ($this->meminstance->getResultCode() == \Memcached::RES_NOTFOUND) {
                if (is_null($callback))
                    return $output;
                return call_user_func($callback, $this->meminstance);
            }
        }
        return $output;
    }

use \AppSingleton;
}
