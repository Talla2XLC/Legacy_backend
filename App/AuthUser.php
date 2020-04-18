<?php

namespace App;

class AuthUser
{
    public function auth()
    {
        $token = $this->getToken();
    }
    public function getToken()
    {
        $max = 10;
        $abc = array('q','w','e','r','t','y','u','i','o','p','a','s');
        for($i=0; $i < $max; $i++){
            $rand = rand(0,9);
            $abcRandom = $abc[$rand];
            if($rand % 2 == 0){
                $random .= $rand;
            }else{
                $random .= $abcRandom;
            }
            
        }
        $shaRand = sha1($random);
        $sol = $random;
        $token = $random.$shaRand;       
    }
}