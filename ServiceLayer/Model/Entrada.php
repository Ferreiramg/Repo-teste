<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Model;

/**
 * Description of Entrada
 *
 * @author Administrador
 */
class Entrada extends \Base\Entradas {

    public $args;

    public function __construct() {
        $this->args = array(
            'id' => ['filter' => FILTER_VALIDATE_INT],
            'produtor' => ['filter' => FILTER_VALIDATE_INT],
            'tipo' => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 1, 'max_range' => 2]],
            'peso' => FILTER_SANITIZE_NUMBER_INT,
            'humidade' => ['filter' => FILTER_VALIDATE_FLOAT],
            'impureza' => ['filter' => FILTER_VALIDATE_FLOAT],
            'motorista' => ['filter' => FILTER_SANITIZE_STRING],
            'ticket' => ['filter' => FILTER_SANITIZE_STRING],
            'observacao' => ['filter' => FILTER_SANITIZE_STRING],
            'data' => ['filter' => FILTER_SANITIZE_STRING],
            'acao' => ['filter' => FILTER_SANITIZE_STRING]
        );
        parent::__construct();
    }

    private function checkType($type) {
        $this->type = $type;
        return $type === 1;
    }

    public function create(array $args) {
        
    }

    public function deletar(array $args) {
        
    }

}
