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

    /**
     * Регистрация пользователей
     */
    public function registrationUser($password = null, $email = null)
    {
        $user = $_POST;

        if (isset($user['password'])) {
            $password = $user['password'];
        }
        if (isset($user['email'])) {
            $email = $user['email'];
        }
        if (isset($email) && isset($password)) {
            $model = new Model();
            if (\R::count('account', 'email = ?', array($email)) == 0) {
                $password = password_hash($password, PASSWORD_BCRYPT);
                try {


                    $legacyUser = \R::dispense('account');
                    $legacyUser->email = $email;
                    $legacyUser->passwd = $password;
                    //$legacyUser->expired = time();
                    \R::store($legacyUser);

                    $recordDb = true;
                } catch (RedException $e) {
                    echo $e;
                }
                if (isset($recordDb)) {
                    $sendMail = new SendMail();
                    $result = $sendMail->sendMailForAuth($email);
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

    public function authUser()
    {
        if (!empty($_POST)) {
            if (
                (isset($_POST['password']) && isset($_POST['email'])) &&
                (!empty($_POST['password']) && !empty($_POST['email']))
            ) {
                $password = $_POST['password'];
                $email = $_POST['email'];

                new Model();
                $account = \R::findOne('account', 'email = ?', array($email));
                if ($account != null) {
                    $hash = $account->passwd;
                    $result = password_verify($password, $hash);
                    if ($result) {
                        $id_user = $account->id;
                        
                        $arrayMethod = ['45678','4#67Ga6','#f7r3Y9'];
                        $randKey = rand(0,2);
                        $method = $arrayMethod[$randKey];
                        $token = $this->getToken($id_user,$method);
                        $_SESSION['user_id'] =   $id_user;
                        setcookie('id_user', $account->id, strtotime('+ 1 month'));
                        setcookie('var',$method,strtotime('+ 1 month'));
                        setcookie('token',$token,strtotime('+ 1 month'));
                        echo 'Вы авторизованы';
                    }else{
                        echo 'Пароль не верен';
                    }
                }else{
                    echo 'Нет такого пользователя';
                }
            }
        }
    }

    private function getToken($id_user,$method)
    {
        if($method == '45678'){
            $id_user = sha1('1$|'.$id_user);
            $token = hash('sha256',$id_user);
        }elseif($method == '4#67Ga6'){
            $id_user = sha1(md5('56^7$#'.$id_user));
            $token = hash('sha256',$id_user);
        }elseif($method == '#f7r3Y9'){
            $id_user = md5(sha1('%679sdfx0i349u'.$id_user));
            $token = hash('haval160,4',$id_user);
        }
        return $token;
    }
    public function checkUserEmail()
    {
        $url  = $_POST;

        if (isset($url['token']) && !empty($url['token'])) $token = $url['token'];
        if (isset($url['key']) && !empty($url['key'])) $key = $url['key'];
        if (isset($url['email']) && !empty($url['email'])) $email = $url['email'];

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
                //$email_verified = true;
                $userEmail = $account->email;
                if ($urlEmail == $userEmail) {
                    $acc = \R::exec("UPDATE account SET email_verified = true WHERE email = '" . $userEmail . "'");
                    $id = $account->id;
                    $array = ['error' => '', 'result' => 'true'];
                    //$_SESSION['user'] = '';
                    $_SESSION['user_id'] =   $id;
                    $arrayMethod = ['45678','4#67Ga6','#f7r3Y9'];
                    $randKey = rand(0,2);
                    $method = $arrayMethod[$randKey];
                    $token_auth = $this->getToken($id,$method);
                    $array['error']['coockie'] = setcookie('id_user', $account->id, strtotime('+ 1 month'));
                    $array['error']['coockie'] = setcookie('var',$method,strtotime('+ 1 month'));
                    $array['error']['coockie'] = setcookie('token',$token_auth,strtotime('+ 1 month'));
                    $array['token'] = $token_auth;
                    echo json_encode($array);
                }
                \R::close();
            } else {
                $array = ['error' => 'Произошла ошибка во время аутентификации, попробуйте еще раз!', 'result' => 'false'];
                echo json_encode($array);
            }
        } else {
            $array = ['error' => 1, 'result' => 'false'];
            echo json_encode($array);
        }
    }
    public function setAccount()
    {
        if (isset($_POST['id_account']) && !empty($_POST['id_account'])) $id = $_POST['id_account'];
        if (isset($_POST['first_name']) && !empty($_POST['first_name'])) $first_name = $_POST['first_name'];
        if (isset($_POST['last_name']) && !empty($_POST['last_name'])) $last_name = $_POST['last_name'];
        if (isset($_POST['phone']) && !empty($_POST['phone'])) $phone = (int) $_POST['phone'];

        if (isset($id)) {

            new Model();
            var_dump($phone);
            //$account = \R::load('account',$id);
            if (isset($first_name)) \R::exec("UPDATE account SET first_name = '" . $first_name . "' WHERE id = " . $id);
            if (isset($last_name)) \R::exec("UPDATE account SET last_name = '" . $last_name . "' WHERE id = " . $id);
            if (isset($phone)) \R::exec("UPDATE account SET phone = " . $phone . " WHERE id = " . $id);
            $arr = ['error' => '', 'result' => true];
            echo json_encode($arr);
        }
    }
}
