<?php

namespace App;

use Core\Application;

class Request
{
    public function test()
    {
        //header("Access-Control-Allow-Origin: *");
        //header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
        //header("Access-Control-Allow-Headers: Content-Type, Authorization");
        //add_header 'Access-Control-Allow-Origin' '*';
		# add_header 'Access-Control-Allow-Origin' 'http://localhost:3000';
		//add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
		//add_header 'Access-Control-Allow-Headers' 'Content-Type, Authorization';
        //status_header(401,);
        //header('HTTP/1.1 401 Not fount text');
        http_response_code(401);
        
        echo 'test request';
        $this->getDumpAll();
        echo file_get_contents('php://input');
    }
    private function getDumpAll()
    {
        echo '----------POST------------<br>'."\n";
        Application::dump($_POST);
        echo '-------end/POST------------<br>'."\n";
        echo '----------GET------------<br>'."\n";
        Application::dump($_GET);
        echo '-------end/GET------------<br>'."\n";
        echo '----------SERVER------------<br>'."\n";
        Application::dump($_SERVER);
        echo '-------end/SERVER------------<br>'."\n";
        echo '----------COOKIE------------<br>'."\n";
        Application::dump($_COOKIE);
        echo '-------end/COOKIE------------<br>'."\n";
        echo '----------REQUEST------------<br>'."\n";
        Application::dump($_REQUEST);
        echo '-------end/REQUEST------------<br>'."\n";       

    }
}