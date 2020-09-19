<?php

namespace App;

use Core\Application;
use App\Interfaces\iUsers;
use App\Mail\SendMail;
use Core\Mailer;
use \PHPMailer\PHPMailer\Exception;

class Tester
{
    public function testAuthUser()
    {
        $user = new Users();

        $user->authUser('12345','hrach@hrach.ru');
    }
    /* public function testRecognition(){
        $recogn = new RecognitionFace();
        $recogn->recognizePersone('images/friends2.jpeg');
    } */

    public function testSendMail(){
        $mail = new SendMail();

        $email = "hrach@hrach.ru";
        
        $mailer = new Mailer();

        $mailer->getMailConfig();
        
        try{
            
            $mailer->getMailConfig();
            $auth = new AuthUser();
            $url = $auth->getURL($email);
            $mailer->mail->setFrom('mailbot@memory-lane.ru','Memory Lane');//от кого
            $mailer->mail->addAddress($email,'');//кому
            $mailer->mail->Subject = 'Проверка почты';//тема отправки письма
            $mailer->mail->msgHTML("Здравствуйте. Мы рады что вы заинтересовались ".
            "нашим проектом. Для продолжение переходите по <a href=''>ссылке</a>", 
            __DIR__);//шаблон отправки
            $mailer->mail->send();
            echo "OK";
            }catch(Exception $e){
                echo $e;
            }
            
    }

}