<?php

namespace Model;

/**
 * Description of SistemMail
 *
 * @author Luís Paulo
 */
class SistemMail {

    private $mail;

    public function __construct() {

        $this->mail = new \PHPMailer();

    }

    public function send(array $args) {
        if (!$this->validate($args))
            throw new \Exceptions\ClientExceptionResponse("Parametros incompletos!");
        //var_dump($args);
        if ($args['file']) {
            $this->mail->addAttachment($args['file']);
        }
        $this->mail->From = "luispkiller@gmail.com"; // Seu e-mail
        $this->mail->FromName = 'Agro Vertentes'; // Seu nome
        $this->mail->addAddress($args['mail'], $args['name']);
        $this->mail->isHTML(true);                                  // Set email format to HTML
        $this->mail->Subject = (string) $args['subject'];
        $this->mail->Body = (string) $args['body'];
        $this->mail->AltBody = strip_tags($args['body']);

        if (!$this->mail->send()) {
            echo 'Mailer Error: ' . $this->mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }

    private function validate(array $args) {
        return $args['mail'] && $args['name'] && $args['body'];
    }

}
