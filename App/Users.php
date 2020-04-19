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
    }

    public function setUser()
    {
    }

    public function authUser()
    {


        if (isset($_POST['name'])) {
            $name = $_POST['name'];
        }
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        }
        if (isset($name) && isset($email)) {
            //тут сохроняем в DB
        }
        $auth = new AuthUser();
        $auth->getURL($email);
        
    }

    public function checkUserEmail()
    {
        if (!empty($_POST)) {
            $item = $_POST;
            if (isset($item['token']) && isset($item['memory'])) {
                $authUser = new AuthUser();
                
                $result = $authUser->checkEmailUser($item['token'], $item['memory']);
                if ($result) {
                    $array = ['result' => 'true'];
                    echo json_encode($array);
                    $model = new Model();
                    $urlEmail = $_POST['email'];
                    $user = \R::dispense('account');
                    $user->isEmailVerified = true;
                    \R::store($user);
                } else {
                    $array = ['result' => 'false'];
                    echo json_encode($array);
                }
            }
        }
    }
}
