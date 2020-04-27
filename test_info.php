<?php
header('Content-Type: image/jpeg');
$name = $_FILES['userfile']['name'];
        $type = $_FILES['userfile']['type'];
        $img = file_get_contents($type);
$file = 'persona.jpeg';
$f = fopen($file,"r");
$img = file_get_contents($file);
echo $img;