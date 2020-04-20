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

    public function authUser($name,$email)
    {
        $user = $_POST;

        if (isset($user['name'])) {
            $name = $user['name'];
        }
        if (isset($user['email'])) {
            $email = $user['email'];
        }
        if (isset($email) && isset($name)) {
            $model = new Model();
            try {
                $legacyUser = \R::dispense('account');
                $legacyUser->first_name = $name;
                $legacyUser->email = $email;
                $legacyUser->expired = 'now';
                \R::store($legacyUser);
                $recordDb = true;              
            } catch (RedException $e) {
                //cho "Такой Email уже существует!!!";
                $recordDb = false;
                $array = ['error'=>print_r($e),'result'=>'Такой Email уже существует!!!'];
                echo json_encode($array);                
            }
            if($recordDb){
                $sendMail = new SendMail();
                $result = $sendMail->sendMailForAuth($email, $name);
                if($result){
                    $array = ['error'=>'','result'=>true];
                    echo json_encode($array);
                }else{
                    $array = ['error'=>'Ошибка при отправки почты: '.$result,'result'=>false];
                    echo json_encode($array);
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
