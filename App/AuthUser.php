<?php

namespace App;



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
        $genURL = '/auth/confirm/?token='.$token['cash'].'&memory='.$token['random'].'&email='.$email;
        //echo $genURL;
        //header("Location: {$genURL}");
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

    public function checkEmailUser(string $token, string $key)
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