<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/bootstrap/App.php';
use Bootstrap\App;
//$app = new App();
//$app->run();
$app = new App();
 
$app->run();