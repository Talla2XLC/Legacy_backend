<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once 'config_api.php';
$data = [
    'client_id'=>'mcs9883751818.ml.vision.jFW25ocYnpgdK4jpRSF5',
    'client_secret'=>'4MBG481H7kAW2bY2Jyx5mbWtqnoKeTsai5wFqAEoi9bKovDHjiDfMPnkAKrn3Q',
    'grant_type'=>'client_credentials'
];
$url = "https://mcs.mail.ru/auth/oauth/v1/token";


$post_data = http_build_query($data);
$boundary = uniqid();
$delimiter = '-------------' . $boundary;
//$post_data = build_data_files($boundary, $fields, $files);
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  #CURLOPT_VERBOSE => true,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_POST => 1,
  CURLOPT_POSTFIELDS => $post_data,
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/x-www-form-urlencoded; boundary=".$delimiter
  ),
));

$response = curl_exec($curl);

$info = curl_getinfo($curl);
//echo "code: ${info['http_code']}\n";
print_r($response);
//$json = json_decode($response);
//var_dump($json);

curl_close($curl);

function build_data_files($boundary, $fields, $files)
{
    $data = '';
    $eol = "\r\n";

    $delimiter = '-------------' . $boundary;

    foreach ($fields as $name => $content)
    {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
            . $content . $eol;
    }

    foreach ($files as $name => $content)
    {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $name . '"' . $eol
            . 'Content-Type: image/jpeg'.$eol
            . 'Content-Transfer-Encoding: binary'.$eol
            ;

        $data .= $eol;
        $data .= $content . $eol;
    }
    $data .= "--" . $delimiter . "--".$eol;
    return $data;
}

?>