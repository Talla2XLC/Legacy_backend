<?php

namespace App\Mail;

use Core\Mailer;
use \PHPMailer\PHPMailer\Exception;
use App\AuthUser;

class SendMail
{
    /**
     * Это функция проверки на существование почты + рассылки почты 
     * @param string $email почтовый адрес
     * @param string $name имя пользователя 
     * @return boolen возврашет об удачной или не удачной отправки почты
     */
    public function sendMailForAuth(string $email)
    {
        try{
        $mailer = new Mailer();
        $mailer->getMailConfig();
        $auth = new AuthUser();
        $url = $auth->getURL($email);
        $mailer->mail->setFrom('mailbot@memory-lane.ru','Memory Lane');//от кого
        $mailer->mail->addAddress($email,'');//кому
        $mailer->mail->Subject = 'Проверка почты';//тема отправки письма
        $mailer->mail->msgHTML("Здравствуйте. Мы рады что вы заинтересовались ".
        "нашим проектом. Для продолжение переходите по <a href='{$url}'>ссылке</a>", 
        __DIR__);//шаблон отправки
        $mailer->mail->send();
        return true;
        }catch(Exception $e){
            return $e;
        }
        
    }
}