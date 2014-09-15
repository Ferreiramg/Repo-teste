<?php

namespace Model;

/**
 * Description of SistemMail
 *
 * @author Luís Paulo
 */
class SistemMail {

    private $mail;
    public $dir;

    public function __construct() {
        $this->mail = new \PHPMailer();
    }

    public function getErrorSendMail() {
        return $this->mail->ErrorInfo;
    }

    public function send(array $args) {
        if (($msg = $this->invalidate($args)))
            throw new \Exceptions\ClientExceptionResponse($msg);

        if (!empty($args['file'])) {
            $this->mail->addAttachment($args['file']);
        }
        $this->mail->From = "luispkiller@gmail.com"; // Seu e-mail
        $this->mail->FromName = 'Agro Vertentes'; // Seu nome
        $this->mail->addAddress($args['mail'], $args['name']);
        $this->mail->isHTML(true);                                  // Set email format to HTML
        $this->mail->Subject = (string) $args['subject'];
        $this->mail->Body = (string) $args['body'];
        $this->mail->AltBody = strip_tags($args['body']);
        $this->mail->send();
        return !$this->mail->isError();
    }

    private function invalidate(array $args) {
        if (empty($args['mail'])) {
            return "E-mail não é valido!";
        }
        if (empty($args['name']) || empty($args['body'])) {
            return "Parametros imcompletos (nome, mensagem)!";
        }
        return false;
    }

}