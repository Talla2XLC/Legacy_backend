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
                    $legacyUser->passwd = 'HASHED VALUE';
                    //$legacyUser->expired = time();
                    \R::store($legacyUser);

                    $recordDb = true;
                } catch (RedException $e) {
                    echo $e;
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
        $url  = $_POST;

        if (isset($url['token']) && !empty($url['token'])) $token = $url['token'];
        if (isset($url['key']) && !empty($url['key'])) $key = $url['key'];
        if (isset($url['email']) && !empty($url['email'])) $email = $url['email'];
        //echo $token;
        //$token = '39b3114da0683dab17881b3cb1108489d44330a01dfefec17bd89e65200e9c8c';
        //$key = '7kjgwusoag5wip758p9e';
        //$email = "hrach@hrach.ru";
        if (isset($token) && isset($key) && isset($email)) {
            //$item = $_POST;
            //if (!isset($item['token']) && !isset($item['memory']) && !isset($item['email'])) {
            $authUser = new AuthUser();
            //print_r($_POST);
            $result = $authUser->checkToken($token, $key);
            if ($result) {
                $model = new Model();
                $urlEmail = $email;
                $account = \R::findOne('account', "email = ?", array($urlEmail));
                $email_verified = true;

                $userEmail = $account->email;
                if ($urlEmail == $userEmail) {
                    $acc = \R::exec("UPDATE account SET email_verified = true WHERE email = '".$userEmail."'");                   
                    $array = ['result' => 'true'];
                    //$_SESSION['user'] = '';
                    echo json_encode($array);
                }
            } else {
                $array = ['result' => 'false'];
                echo json_encode($array);
            }
        } else {
            echo 'Нет параметров.';
        }
    }
    public function setAccount()
    {

    }
    
}
