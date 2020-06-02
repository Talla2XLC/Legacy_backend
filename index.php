<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/bootstrap/App.php';
use Bootstrap\App;
//$app = new App();
//$app->run();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Origin, X-Requested-With, Accept");
$app = new App();
 
$app->run();