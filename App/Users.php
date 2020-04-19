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
                $legacyUser = \R::dispense('account');
                $legacyUser->first_name = $name;
                $legacyUser->email = $email;
                \R::store($legacyUser);
                $sendMail = new SendMail();
                $result = $sendMail->sendMailForAuth($email, $name);
                if($result){
                    $array = ['error'=>'','result'=>'true'];
                    echo json_encode($array);
                }
            } catch (RedException $e) {
                //cho "Такой Email уже существует!!!";
                $array = ['error'=>'true','result'=>'Такой Email уже существует!!!'];
                echo json_encode($array);
                if ($_POST['type'] == 'dev') {
                    echo $e;
                    echo '<br>';
                }                
            }
            
        }
        
        
    }

    public function checkUserEmail()
    {
        if (!empty($_POST)) {
            $item = $_POST;
            if (isset($item['token']) && isset($item['memory']) && isset($item['email'])) {
                $authUser = new AuthUser();
                
                $result = $authUser->checkEmailUser($item['token'], $item['memory']);
                if ($result) {                    
                    $model = new Model();
                    $urlEmail = $_POST['email'];
                    $user = \R::dispense('account');
                    $userEmail = $user->email;
                    if($urlEmail == $userEmail){
                        $user->isEmailVerified = true;
                        \R::store($user);
                        $array = ['result' => 'true'];
                        echo json_encode($array);
                    }
                    
                } else {
                    $array = ['result' => 'false'];
                    echo json_encode($array);
                }
            }
        }
    }
}
