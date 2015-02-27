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
    }

    /**
     * Check is data on memcache
     * @param string $key
     * @param \Closure $callback function
     * @return string json
     */
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

    /**
     * Delete all data
     * @return boolean
     */
    public function delete() {
        return $this->meminstance->deleteMulti($this->meminstance->getAllKeys());
    }

use \AppSingleton;
}
