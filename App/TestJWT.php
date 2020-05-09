<?php

namespace App;

use Core\JWT;

class TestJWT 
{
    public function getJWT()
    {
        $id = 50;
        $time = time();
        echo $time;
        $data = $id.'.'.$time;
        $jwt = new JWT();
        $chifr = $jwt->JWT_encode($data);
       
        echo $chifr;
        echo '<br>';
        //list($id,$iv,$tag) = explode('.',$token);
        $decod =  $jwt->JWT_decode($chifr);
        echo $decod;
        list($i,$t) = explode('.',$decod);
        $timer = time() - 1588872734;
        $text = 'Прошло '.$timer;
        echo $text;
    }
}