<?php

/*
 * Copyright (C) 2014 Luís Paulo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use \AppSingleton;

/**
 * Description of Configs
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
