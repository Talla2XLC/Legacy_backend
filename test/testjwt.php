<?php
//$intRandom = random_int(0,30);
//echo $intRandom;
$sslByte = openssl_random_pseudo_bytes(30);
$bytes = random_bytes(30);
$var = bin2hex($sslByte);
var_dump($var);
$res = base64_decode('0K3RgtC+INC30LDQutC+0LTQuNGA0L7QstCw0L3QvdCw0Y8g0YHRgtGA0L7QutCw');
//var_dump($res);

$plaintext = "данные для шифрования";
$cipher = "aes-128-gcm";
//$key = $var;
if (in_array($cipher, openssl_get_cipher_methods()))
{
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext = openssl_encrypt($plaintext, $cipher, '34', $options=0, $iv, $tag);
    // сохраняем $cipher, $iv и $tag для дальнейшей расшифровки
    $original_plaintext = openssl_decrypt($ciphertext, $cipher, '34', $options=0, $iv, $tag);
    var_dump($tag);
    $bait = base64_encode($tag);
    $decode_tag = base64_decode($bait);

    var_dump($decode_tag);
    var_dump( $original_plaintext)."\n";
}
