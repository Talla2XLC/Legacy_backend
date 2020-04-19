<?php

namespace App;

use Core\Application;

class AuthUser
{
    private $token;
    private $key;

    public function auth()
    {
        $this->token = $this->getToken();
        //Application::dump($token);
        echo $this->token;
    }
    protected function getToken()
    {
        $max = 10;
        $abc = array('q','w','e','r','t','y','u','i','o','p','a','s');
        $random = '';
        for($i=0; $i < $max; $i++){
            $rand = rand(0,9);
            $abcRandom = $abc[$rand];
            if($rand % 2 == 0){
                $random .= $rand;
            }else{
                $random .= $abcRandom;
            }
            
        }
        $shaRand = hash('sha256',$random);
        $salt = $random;
        $token = $salt.$shaRand;
        $this->key = $random;
        return $token;   
    }
    public function checkToken()
    {
        
    }
}