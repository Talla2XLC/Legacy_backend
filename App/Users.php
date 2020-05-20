<?php

namespace App;

use App\Interfaces\iUsers;
use Core\Model;
use Core\Application;
use Core\Mailer;
use App\Mail\SendMail;
use Core\JWT;
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
        //$account = \R::findCollection('account', "LIMIT $limit OFFSET $start");
        $result = \R::getAll("SELECT 'SELECT ' ||
        ARRAY_TO_STRING(ARRAY(SELECT COLUMN_NAME::VARCHAR(50)
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME='account' AND
                COLUMN_NAME NOT IN ('passwd')
            ORDER BY ORDINAL_POSITION
        ), ', ') || ' FROM account'");
        $sql = $result[0]['?column?'];
        $data = \R::getAll($sql);
        echo json_encode($data);
        //$i = 0;
        /*
        while ($item = $account->next()) {
            $array[$i]['first_name'] = $item['first_name'];
            $array[$i]['last_name'] = $item['last_name'];
            $array[$i]['admin_privileges'] = $item['admin_privileges'];
            $array[$i]['date_created'] = $item['date_created'];
            $array[$i]['email'] = $item['email'];
            $i++;
        }
        */
        //self::dump($array);
        //echo json_encode($array);
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
        //$user = $_POST;
        $users = json_decode(file_get_contents("php://input"));
        //print_r($users);
        //echo $users['email'];
        if (isset($users->password)) {
            $password = $users->password;
        }
        if (isset($users->email)) {
            $email = $users->email;
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


        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data)) {
            if (
                (isset($data->password) && isset($data->email)) &&
                (!empty($data->password) && !empty($data->email))
            ) {
                $password = $data->password;
                $email = $data->email;
                
                new Model();
                $account_email = \R::findOne('account', 'email = ?', array($email));
                $account_email_verified = \R::getAll("SELECT email_verified FROM account WHERE email = '{$email}' AND email_verified = true");
                //print_r($account_email_verified);
                if ($account_email != null) {
                    if ($account_email_verified == null) {
                        $arr = ['error'=>'Вы не подтвердили почту','result'=>false];
                        echo json_encode($arr);
                        exit;
                        
                    }
                    $hash = $account_email->passwd;
                    $result = password_verify($password, $hash);
                    if ($result) {
                        $id_user = $account_email->id;

                        $arrayMethod = ['45678', '4#67Ga6', '#f7r3Y9'];
                        $randKey = rand(0, 2);
                        $method = $arrayMethod[$randKey];
                        //$token = $this->getToken($id_user,$method);
                        //$_SESSION['user_id'] =   $id_user;
                        //setcookie('id_user', $account_email->id, strtotime('+ 1 month'));
                        //setcookie('var',$method,strtotime('+ 1 month'));
                        //setcookie('token',$token,strtotime('+ 1 month'));
                        $jwt = new JWT();
                        $time = time();
                        $token = $jwt->JWT_encode($id_user . '.' . $time);
                        $arr = ['error' => '', 'result' => true, 'token' => $token];
                        echo json_encode($arr);
                    } else {
                        $arr = ['error' => 'Пароль не верен', 'result' => false];
                        echo json_encode($arr);
                    }
                } else {
                    $arr = ['error' => 'Такая почта не существует', 'result' => false];
                    echo json_encode($arr);
                }
            }
        }
    }

    private function getToken($id_user, $method)
    {
        if ($method == '45678') {
            $id_user = sha1('1$|' . $id_user);
            $token = hash('sha256', $id_user);
        } elseif ($method == '4#67Ga6') {
            $id_user = sha1(md5('56^7$#' . $id_user));
            $token = hash('sha256', $id_user);
        } elseif ($method == '#f7r3Y9') {
            $id_user = md5(sha1('%679sdfx0i349u' . $id_user));
            $token = hash('haval160,4', $id_user);
        }
        return $token;
    }
    public function checkUserEmail()
    {
        $data = json_decode(file_get_contents("php://input"));
        //print_r($data);
        if (isset($data->token) && !empty($data->token)) $token = $data->token;
        if (isset($data->key) && !empty($data->key)) $key = $data->key;
        if (isset($data->email) && !empty($data->email)) $email = $data->email;

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

                    //$_SESSION['user'] = '';
                    $_SESSION['user_id'] =   $id;
                    $arrayMethod = ['45678', '4#67Ga6', '#f7r3Y9'];
                    $randKey = rand(0, 2);
                    $method = $arrayMethod[$randKey];
                    $token_auth = $this->getToken($id, $method);
                    $array = ['error' => '', 'result' => true];
                    //$array['error']['coockie'] = setcookie('id_user', $account->id, strtotime('+ 1 month'));
                    //$array['error']['coockie'] = setcookie('var',$method,strtotime('+ 1 month'));
                    //$array['error']['coockie'] = setcookie('token',$token_auth,strtotime('+ 1 month'));
                    //$array['token'] = $token_auth;

                    echo json_encode($array);
                }
                \R::close();
            } else {
                $array = ['error' => 'redirection', 'result' => 'false'];
                echo json_encode($array);
            }
        } else {
            $array = ['error' => 'redirection', 'result' => 'false'];
            echo json_encode($array);
        }
    }
    public function setAccount()
    {

        $data = json_decode(file_get_contents("php://input"), true);
        // print_r($data);
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $jwt = new JWT();
            $token = $_SERVER['HTTP_AUTHORIZATION'];
            $dt = $jwt->JWT_decode($token);
            if ($dt != null) {
                list($id, $time) = explode('.', $dt);
            }
        }      


        if (isset($id) && $id != "") {
            //var_dump($asked_to_introduce);
            new Model();
            $result = \R::getAll("SELECT 'SELECT ' ||
        ARRAY_TO_STRING(ARRAY(SELECT COLUMN_NAME::VARCHAR(50)
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME='account' AND
                COLUMN_NAME NOT IN ('passwd')
            ORDER BY ORDINAL_POSITION
        ), ', ') || ' FROM account WHERE id = {$id}'");
            $sql = $result[0]['?column?'];
            $dbData = \R::getAll($sql);
            //print_r($data);
            //echo json_encode($data);
            //$user = \R::findOne('account', 'id = ?', array($id));
            if (!empty($data)) {
                //$data = (array) $data;
                //print_r($data);
                $dbDataUser = \R::load('account', $id);
                if (is_array($data)) {
                    // echo 'is array';
                    $sql = "UPDATE account SET";
                    $i = 0;
                    $count = count($data);
                    foreach ($data as $key => $item) {

                        if ($i == 0) {
                            $sql .= " {$key} = '{$item}'";
                        } else {
                            $sql .= ", {$key} = '{$item}'";
                        }

                        $i++;
                    }
                    $sql .= " WHERE id = {$id}";
                    $result = \R::exec($sql);
                    if ($result) {
                        $arr = ['error' => '', 'result' => true];
                        echo json_encode($arr);
                    }
                }
            } else {
                echo json_encode($dbData[0]);
                exit;
            }
            /*
            if (isset($phone) || isset($last_name) || isset($first_name) || isset($asked_to_introduce)) {

                if (isset($first_name)) $result = \R::exec("UPDATE account SET first_name = '" . $first_name . "' WHERE id = " . $id);
                if (isset($last_name)) $result = \R::exec("UPDATE account SET last_name = '" . $last_name . "' WHERE id = " . $id);
                if (isset($phone)) $result = \R::exec("UPDATE account SET phone = " . $phone . " WHERE id = " . $id);
                if (isset($asked_to_introduce)) $result = \R::exec("UPDATE account SET asked_to_introduce = " . $asked_to_introduce . " WHERE id = " . $id);
                if (isset($result) && $result == 1) {
                    $arr = ['error' => '', 'result' => true];
                    echo json_encode($arr);
                }
            } else {
                echo json_encode($data[0]);
                exit;
            }
            */

            //var_dump($phone);
            //$account = \R::load('account',$id);


        } else {
            $arr = ['error' => 'Вы не авторизованы', 'result' => false];
            echo json_encode($arr);
        }
        //echo 'ничего';
    }

    public function checkToken()
    {

        //header("Access-Control-Allow-Origin: *");
        //header("Access-Control-Allow-Methods: POST, OPTIONS");
        //header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        //header("Content-Type: application/json");
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = $_SERVER['HTTP_AUTHORIZATION'];
            $jwt = new JWT();
            $data = $jwt->JWT_decode($token);
            if ($data != null) {
                list($id, $time) = explode('.', $data);
                if (isset($data) && isset($time)) {
                    //header('HTTP/1.0 200 OK');
                    //http_response_code(200);
                    $arr = ['error' => '', 'result' => true];
                    echo json_encode($arr);
                }
            } else {
                //header('Access-Control-Allow-Origin: *');
                //header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
                //header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
                //header('Content-Type: application/json');
                $method = $_SERVER['REQUEST_METHOD'];
                if ($method == "POST") {
                    // header('Access-Control-Allow-Origin: *');
                    //header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
                    //header("Status: 401 Unauthorized");
                    //http_response_code(401);
                    // header('HTTP/1.0 401 Unauthorized');
                    $arr = ['error' => '', 'result' => false];
                    echo json_encode($arr);
                }
            }
        } else {
            //header('HTTP/1.0 401 Unauthorized');
            echo json_encode(['error' => '401']);
        }
    }
}
