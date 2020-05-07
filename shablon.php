<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once 'config_api.php';
$url = "https://mcs.mail.ru/auth/oauth/v1/token?client_id={$id_user}&client_secret={$secret}&grant_type=client_credentials";
$path = 'test';
$meta = '{"space":"1", "images":[{"name":"friends.jpeg"}]}';

$filenames = array($path);
$files = array();
foreach ($filenames as $f)
{
    if (strlen($f) == 0)
    {
        # just to make correct multipart/form-data request
        $files[$f] = 'fake content';
    }
    else
    {
        $files[$f] = file_get_contents($f);
    }
}

$boundary = uniqid();
$delimiter = '-------------' . $boundary;

$fields = array("meta"=> $meta);
$post_data = build_data_files($boundary, $fields, $files);
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  #CURLOPT_VERBOSE => true,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_POST => 1,
  CURLOPT_POSTFIELDS => $post_data,
  CURLOPT_HTTPHEADER => array(
    "Content-Type: multipart/form-data; boundary=" . $delimiter,
    "Content-Length: " . strlen($post_data),
  ),
));

$response = curl_exec($curl);

$info = curl_getinfo($curl);
//echo "code: ${info['http_code']}\n";
//var_dump($response);
$json = json_decode($response);
var_dump($json);
//echo $json->body;
curl_close($curl);



?>