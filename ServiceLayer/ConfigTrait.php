<?php

use \AppSingleton;

/**
 * Abstract Config class access
 *
 * @author Luís Paulo
 */
trait ConfigTrait {

    use AppSingleton;

    private $_document;

    public function __get($name) {
        return (object) $this->_document->get($name);
    }

    public function __call($name, array $arguments) {
        return call_user_func_array([$this->_document, $name], $arguments);
    }

    protected function exists($file) {
        return file_exists($file);
    }

}
