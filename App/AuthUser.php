<?php

namespace App;

use Core\Application;
use Core\Model;

class AuthUser
{
    private $token;
    private $key;
    /**
     * Функия генерирует URL для отправки по почте чтобы потом подвердить почту
     * @param string $email почта пользователя
     */
    public function getURL(string $email)
    {
        $token = $this->getToken();
        $genURL = 'http://dev.memory-lane.ru/check/auth-email/?token='.$token['cash'].'&memory='.$token['random'].'&email='.$email;
        //$genURL = 'http://localhost/Legacy_backend/test/check_user.php?token='.$token['cash'].'&memory='.$token['random'].'&email='.$email;
        //echo $genURL;
        //header("Location: {$genURL}");
        //$genURL = 'http://legacy.loc/test/check_user.php?token='.$token['cash'].'&memory='.$token['random'].'&email='.$email;
        return $genURL;
    }
    public function getToken()
    {
        $max = 20;
        $abc = array('q','w','e','r','t','y','u','i','o','p','a','s','d','f','g','h','j','k','l','z');
        
        $random = '';
        for($i=0; $i < $max; $i++){
            $rand = rand(1,9);
            $rand_for_array = rand(0,19);
            $abcRandom = $abc[$rand_for_array];
            if($i % $rand == 0){
                $random .= $rand;
            }else{
                $random .= $abcRandom;
            }
            
        }
        $salt = '1k';//salt
        $shaRand = hash('sha256',sha1(md5($salt.$random)));
        $token['cash'] = $shaRand;
        $token['random'] = $random;
        return $token;     
    }

    public function checkToken(string $token, string $key)
    {
        $salt = '1k';
        $item = $salt.$key;
        $hash = hash('sha256',sha1(md5($item)));
        if($hash == $token){
            return true;
        }else{
            return false;
        }        
    }
}