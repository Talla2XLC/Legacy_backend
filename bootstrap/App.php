<?php

namespace Bootstrap;

use Core\Router;


class App 
{
    public function run()
    {
        //require_once 'config/Doctrine.php';

        session_start();
        $route = new Router();
        $route->redirection();
    }
}