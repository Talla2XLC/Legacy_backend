<?php

namespace App;

use Core\Application;

class AuthUser
{
    private $token;
    private $key;

    public function auth()
    {
        $token = $this->getToken();
        $genURL = '/auth/confirm/?token='.$token['cash'].'&memory='.$token['random'];
        echo $genURL;
        //header("Location: {$genURL}");
    }
    protected function getToken()
    {
        $max = 20;
        $abc = array('q','w','5','r','2','y','u','i','3','p','a','s','7','d','f','0','4','3','9','1');
        
        $random = '';
        for($i=0; $i < $max; $i++){
            $rand = rand(0,9);
            $rand_for_array = rand(0,19);
            $abcRandom = $abc[$rand_for_array];
            if($i % 2 == 0){
                $random .= $rand;
            }else{
                $random .= $abcRandom;
            }
            
        }
        $shaRand = hash('sha256',sha1(md5($random)));
        $sol = $random;
        $token['cash'] = $shaRand;
        $token['random'] = $random;
        return $token;     
    }

    public function test()
    {
        //$secret = $_POST['secret'];
        $pass = 'test';
        $sh1 = sha1(md5($pass));
        echo $sh1."<br>";
        //$hash = hash('sha256',$sh1);
        
        //$sc_hash = $this->getHash($pass);
        /*
        if($hash == $sc_hash){
            echo 'OK';
        }else{
            echo 'No';
        }
        */
    }

    public function getHash($secret)
    {
        $sc = sha1($secret);
        echo $sc."<br>";
        //return hash('sha256',$sc);
    }
}