<?php

namespace App;

use App\Interfaces\iUsers;
use Core\Model;
use Core\Application;
use Core\Mailer;
use App\Mail\SendMail;
use \RedBeanPHP\RedException;


class Users extends Application implements iUsers
{
    public function all()
    {
        //echo 'Сласс Users, метод all';
    }

    public function setUser()
    {
    }

    public function authUser()
    {
        $model = new Model();

        $user = $_POST;

        if (isset($user['name'])) {
            $name = $user['name'];
        }
        if (isset($user['email'])) {
            $email = $user['email'];
        }
        if (isset($email) && isset($name)) {

            try {
                $legacyUser = \R::dispense('person_temp');
                $legacyUser->first_name = $name;
                $legacyUser->email = $email;
                \R::store($legacyUser);
            } catch (RedException $e) {
                echo "Такой Email уже существует!!!";
                if ($_POST['type'] == 'dev') {
                    echo $e;
                    echo '<br>';
                }                
            }
            $sendMail = new SendMail();
            $sendMail->sendMailForAuth($email, $name);
        }
    }
}
