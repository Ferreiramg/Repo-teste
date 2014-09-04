<?php

namespace Client;

/**
 * Description of Email
 *
 * @author Luis Paulo
 */
class Email extends AbstracClient {

    public function execute() {
        $model = new \Model\SistemMail();
        $post = filter_input_array(INPUT_POST, [
            'name' => FILTER_SANITIZE_STRING,
            'subject' => FILTER_SANITIZE_STRING,
            'mail' => FILTER_SANITIZE_EMAIL,
            'body' => FILTER_SANITIZE_STRING,
            'data' => 0, 'acao' => 0
        ]);
        $post['file'] = $this->upload();
        $model->send($post);
    }

    public function hasRequest() {
        return \Main::$Action === 'sendmail';
    }

    private function upload() {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
            $dir = ROOT . \Configs::getInstance()->upload_dir;
            if (!file_exists($dir))
                mkdir($dir);
            $res = move_uploaded_file($_FILES['file']['tmp_name'], $file = (string) $dir . $_FILES['name']);
            if ($res)
                return $file;
        }
        return null;
    }

}
