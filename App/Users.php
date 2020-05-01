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
    /**
     * Функция передает лимитированый список пользователей
     */
    public function all()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        //print_r($request);
        $model = new Model();
        
        $limit = '30';
        $start = '0';
        $account = \R::findCollection('account', "LIMIT $limit OFFSET $start");
        $i = 0;

        while ($item = $account->next()) {
            $array[$i]['first_name'] = $item['first_name'];
            $array[$i]['last_name'] = $item['last_name'];
            $array[$i]['admin_privileges'] = $item['admin_privileges'];
            $array[$i]['date_created'] = $item['date_created'];
            $array[$i]['email'] = $item['email'];
            $i++;
        }
        //self::dump($array);
        echo json_encode($array);
        //$name = $account->first_name;
        
    }

    public function setUser()
    {
    }

    public function authUser($name, $email)
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

            if (\R::count('account', 'email = ?', array($email)) == 0) {
                try {


                    $legacyUser = \R::dispense('account');
                    $legacyUser->first_name = $name;
                    $legacyUser->email = $email;
                    $legacyUser->password = '';
                    $legacyUser->expired = time();
                    \R::store($legacyUser);

                    $recordDb = true;
                } catch (RedException $e) {
                    //echo $e;         
                }
                if (isset($recordDb)) {
                    $sendMail = new SendMail();
                    $result = $sendMail->sendMailForAuth($email, $name);
                    if ($result) {
                        $array = ['error' => '', 'result' => true];
                        echo json_encode($array);
                    } else {
                        $array = ['error' => 'Ошибка при отправки почты: ' . $result, 'result' => false];
                        echo json_encode($array);
                    }
                }
                \R::close();
            } else {
                $array = ['error' => 'Такая почта уже существует!', 'result' => false];
                echo json_encode($array);
            }
        }
    }

    public function checkUserEmail()
    {
        if (!empty($_POST)) {
            $item = $_POST;
            if (isset($item['token']) && isset($item['memory']) && isset($item['email'])) {
                $authUser = new AuthUser();

                $result = $authUser->checkToken($item['token'], $item['memory']);
                if ($result) {
                    $model = new Model();
                    $urlEmail = $_POST['email'];
                    $account = \R::find('account', 'email = '.$urlEmail);
                    Application::dump($account);
                    /*
                    $userEmail = $user->email;
                    if ($urlEmail == $userEmail) {
                        $user->isEmailVerified = true;
                        \R::store($user);
                        $array = ['result' => 'true'];
                        echo json_encode($array);
                    }*/
                } else {
                    $array = ['result' => 'false'];
                    echo json_encode($array);
                }
            }
        }
    }
}
