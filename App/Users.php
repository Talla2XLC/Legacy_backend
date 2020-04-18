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

        if(isset($user['name'])){
            $name = $user['name'];
                        
        }
        if(isset($user['email'])){
            $email = $user['email'];
        }

        $legacyUser = R::dispense('person_temp');
        $legacyUser->first_name = $name;
        $legacyUser->email = $email;
        R::store($legacyUser);

    }
}
