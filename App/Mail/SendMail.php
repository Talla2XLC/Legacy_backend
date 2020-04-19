<?php

namespace App\Mail;

use Core\Mailer;
use \PHPMailer\PHPMailer\Exception;

class SendMail
{
    /**
     * Это функция проверки на существование почты + рассылки почты 
     * @param string $email почтовый адрес
     * @param string $name имя пользователя 
     * @return boolen возврашет об удачной или не удачной отправки почты
     */
    public function sendMailForAuth(string $email,string $name)
    {
        try{
        $mailer = new Mailer();

        $mailer->mail->setFrom('mailbot@memory-lane.ru');//от кого
        $mailer->mail->addAddress($email, $name);//кому
        $mailer->mail->Subject = 'Проверка почты';//тема отправки письма
        $mailer->mail->msgHTML(file_get_contents(''), __DIR__);//шаблон отправки
        }catch(Exception $e){
            echo "{$e}";
        }
        
    }
}