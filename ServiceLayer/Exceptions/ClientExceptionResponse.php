<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Exceptions;

/**
 * Description of ClientExceptionResponse
 *
 * @author Administrador
 */
class ClientExceptionResponse extends \Exception {

    protected $message = "", $code = 0, $severity;

    const DANGER = "error", SUCCESS = "success", INFO = "info";

    public function __construct($message, $code = 0, $severity = self::DANGER) {
        $this->message = $message;
        $this->code = $code;
        $this->severity = $severity;
    }

    public function renderJsonMessage() {
        return json_encode([
            'message' => (string) $this->message,
            'code' => (int) $this->code,
            'severity' => (string) $this->severity]);
    }

}
