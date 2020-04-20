<?php

namespace App;

use Core\Application;
use App\Interfaces\iUsers;

class Tester
{
    public function testAuthUser()
    {
        $user = new Users();

        $user->authUser('Грачья','hrach333@gmail.com');
    }
    

}