<?php

namespace Bootstrap;

use Core\Router;


class App 
{
    public function run()
    {
        session_start();
        $route = new Router();
        $route->redirection();
    }
}