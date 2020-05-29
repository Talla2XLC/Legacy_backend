<?php

namespace App;

use Core\Application;
use App\Interfaces\iUsers;

class Tester
{
    public function testAuthUser()
    {
        $user = new Users();

        $user->authUser('12345','hrach@hrach.ru');
    }
    public function testRecognition(){
        $recogn = new RecognitionFace();
        $recogn->recognizePersone('images/friends2.jpeg');
    }

}