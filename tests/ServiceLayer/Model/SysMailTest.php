<?php

use GuzzleHttp\Client;
use GuzzleHttp\Post\PostFile;
use \PHPUnit_Framework_TestCase as PHPUnit;

class SysMailTest extends PHPUnit {

    protected $tmpf;
    
    protected function setUp(){
        $tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        file_put_contents($this->tmpf = $tmp . 'tmp_up.txt', "Upload File Test!!!!!!!!");
    }
    protected function tearDown(){
        @unlink($this->tmpf);
    }
    public function testSendMail() {
//        $client = new Client();
//        $response = $client->post('http://localhost/sendmail', ['body' => [
//                'name' => 'luis Paulo',
//                'subject' => 'Email Testes',
//                'mail' => 'luispaulo@laticiniospj.com.br',
//                'body' => '<b>Email de Teste com PHPMailer...</b>'
//        ]]);
//        $this->assertEquals($response->getStatusCode(), '200');
//        echo $response->getBody();
    }

}
