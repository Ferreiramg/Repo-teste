<?php

namespace Client;

/**
 * Description of Email
 *
 * @author Luis Paulo
 */
class Email extends AbstracClient {

    /**
     * @codeCoverageIgnore
     */
    public function execute() {
        $model = new \Model\SistemMail();
        $post = filter_input_array(INPUT_POST, [
            'name' => FILTER_SANITIZE_STRING,
            'subject' => FILTER_SANITIZE_STRING,
            'mail' => FILTER_VALIDATE_EMAIL,
            'body' => FILTER_SANITIZE_STRING,
            'data' => 0, 'acao' => 0]
        );
        $post['file'] = $model->attachement();
        if (!$model->send($post) && $model->getErrorSendMail() !== '') {
            @unlink($post['file']);
            throw new \Exceptions\ClientExceptionResponse($model->getErrorSendMail());
        }
        @unlink($post['file']);
        printf('{"code":"%s"}', 1); //true for success
    }

    public function hasRequest() {
        return \Main::$Action === 'sendmail';
    }

}
