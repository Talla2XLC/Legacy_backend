<?php

namespace App;

use App\Interfaces\iUsers;
use Core\Model;
use Core\Application;
use Core\Mailer;

class Users extends Application implements iUsers
{
    public function all()
    {
        //$model = new Model();
        $mailer = new Mailer();
        

        
        //echo 'Сласс Users, метод all';
    }

    public function setUser()
    {
          
    }

    public function authUser()
    {
        $model = new Model();

        $user = $_POST;

        /*if(isset($user))(
            // Создаем массив для сбора ошибок
            $errors = array();
            
            if(R::count('person_temp', "email = ?", array($user['email'])) > 0) {

                $errors[] = "Пользователь с таким Email существует!";
            }
        )*/
        
        if(isset($user['name'])){
            $name = $user['name'];
                        
        }

        try{
            if(isset($user['email'])){
                $email = $user['email'];
            }
            $legacyUser = R::dispense('person_temp');
            $legacyUser->first_name = $name;
            $legacyUser->email = $email;
            R::store($legacyUser);

        }catch (Exception $e) {
            echo "Такой Email уже существует!!!";
        }

        $mailer = new PHPMailer(true);
        try{
            $mailer->setFrom('mailbot@memory-lane.ru');//от кого
            $mailer->addAddress($legacyUser->email, $legacyUser->first_name);//кому
            $mailer->Subject = 'Проверка почты';//тема отправки письма
            $mailer->msgHTML(file_get_contents(''), __DIR__);//шаблон отправки

            if($mailer->send()){
                echo  'сообщение было отправлено';
            }
        }catch (Exception $e) {
            echo 'Mailer Error: {mailer->ErrorInfo}';
        }



    }
}
