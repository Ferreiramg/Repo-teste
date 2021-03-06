<?php

use GuzzleHttp\Client;
use GuzzleHttp\Post\PostFile;
use \PHPUnit_Framework_TestCase as PHPUnit;

class SysMailTest extends PHPUnit {

    protected $tmpf;

    protected function setUp() {
        $tmp = ROOT . Configs::getInstance()->app->upload_dir;
        $this->tmpf = $tmp . 'file';
    }

    protected function tearDown() {
        @unlink($this->tmpf);
    }

    /**
     * @covers \Client\Email::execute
     * @covers \Model\SistemMail::invalidate
     */
    public function testInvalidMail() {
        $this->markTestIncomplete(
                'This test not work in travis ci.'
        );
        $client = new Client();
        $response = $client->post('http://localhost/sendmail', ['body' => [
                'name' => 'luis Paulo',
                'subject' => 'Email Testes',
                'mail' => 'luispkiller@dddd', //wrong
                'body' => '<b>Email de Teste com PHPMailer...</b>'
        ]]);
        $this->assertEquals($response->getStatusCode(), '200');
        $this->expectOutputString('{"message":"E-mail n\u00e3o \u00e9 valido!","code":"0","severity":"error"}');
        echo $response->getBody();
    }

    public function testSendMailWillAttachmentFile() {
        $this->markTestIncomplete(
                'This test not work in travis ci.'
        );
        $client = new Client();
        $response = $client->post('http://localhost/sendmail', ['body' => [
                'name' => 'luis Paulo',
                'subject' => 'Email Testes',
                'mail' => 'luispkiller@gmail.com',
                'body' => '<b>Email de Teste com PHPMailer...</b>',
                'file' => new PostFile('file', 'Upload File Test!!!!!!!!')
        ]]);
        $this->expectOutputString('{"code":"1"}');
        echo $response->getBody();
    }

    public function testSendMailDirect() {

        $mail = new \Model\SistemMail();
        $res = $mail->send([
            'name' => 'luis Paulo',
            'subject' => 'Email Testes',
            'mail' => 'luispaulo@laticiniospj.com.br',
            'body' => '<b>Email de Teste com PHPMailer...</b>'
        ]);
        $this->assertTrue($res);
    }

    /**
     * @expectedException \Exceptions\ClientExceptionResponse
     */
    public function testSendMailExceptionInvalidParamters() {

        $mail = new \Model\SistemMail();
        $mail->send(['mail' => 'luispkiller@gmail.com']);
    }

    /**
     * @expectedException \Exceptions\ClientExceptionResponse
     */
    public function testSendMailExceptionInvalidParamtersMailNotFound() {

        $mail = new \Model\SistemMail();
        $mail->send(['name' => 'luis Paulo','body' => '<b>Email de Teste com PHPMailer...</b>']);
    }

    public function testInvalitAttachemetFile() {

        try {
            $mail = new \Model\SistemMail();
            $mail->send([
                'name' => 'luis Paulo',
                'subject' => 'Email Testes',
                'mail' => 'luispkiller@gmail.com',
                'body' => '<b>Email de Teste com PHPMailer...</b>',
                'file' => 'not_found_file.error'
            ]);
        } catch (\Exception $e) {
            
        }
        $this->assertEquals($mail->getErrorSendMail(), "Could not access file: not_found_file.error");
    }

}
