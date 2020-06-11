<?php
header('Content');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once 'config_api.php';
$data = [
    'client_id'=>'mcs9883751818.ml.vision.jFW25ocYnpgdK4jpRSF5',
    'client_secret'=>'4MBG481H7kAW2bY2Jyx5mbWtqnoKeTsai5wFqAEoi9bKovDHjiDfMPnkAKrn3Q',
    'grant_type'=>'client_credentials'
];
$url = "https://smarty.mail.ru/api/v1/persons/truncate?oauth_provider=mcs&oauth_token=EeMGiQ3qah52CKHt3DwJZ1ZBQbfi7dp9JsV4fFHeZsqBbPfz3";
$path = 'http://pictures.govoru.com/news/2013/11/27/8676/da75219e.jpg';
//$meta = '{"space":"1", "images":[{"name":"http://pictures.govoru.com/news/2013/11/27/8676/da75219e.jpg"}], "create_new":false}';
$meta = '{"space":"1"}';
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
//$post_data = http_build_query($data);
$fields = array("meta"=> $meta);
$boundary = uniqid();
$delimiter = '-------------' . $boundary;
$curl = curl_init();
$post_data = build_data_files($boundary, $fields, $files);
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


curl_close($curl);
echo $response;
//$json = json_decode($response);
/*
$info = $json->body->objects[0];
$info = $info->persons;
//print_r($info);
$i =0;
$person = array('person1'=>'Джесика','person2'=>'Кристина');
foreach($info as $item){
    //print_r($item);
    $arr[$i]['coord'] = $item->coord;
    if(!empty($person[$item->tag])){
        $arr[$i]['tag'] = $person[$item->tag];
    }else{
        $arr[$i]['tag'] = $item->tag;
    }
    
    $i++;
}
echo json_encode($arr);
*/
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
    /*
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
    */
    $data .= "--" . $delimiter . "--".$eol;
    return $data;
}

?>