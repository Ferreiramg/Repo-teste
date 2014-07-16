<?php

namespace Exceptions;

/**
 * ClientExceptionResponse
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
