<?php

use App\TestConection;

require_once 'TestConection.php';
$obj = new TestConection();

$name = $_FILES['userfile']['tmp_name'];

$type = $_FILES['userfile']['type'];

$size = $_FILES['userfile']['size'];

$obj->testPutObject($name,$type,$size);
